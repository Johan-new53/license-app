<?php

namespace App\Services;

use App\Models\Payableto;
use App\Models\Sync_log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VendorSyncService
{
    public function sync(string $userEntry = 'system'): array
    {
        $types = ['hardcopy', 'automate'];
        $syncStartedAt = now();

        try {
            $baseUrl  = env('VENDOR_PRF_API_URL');
            $username = env('VENDOR_PRF_API_USERNAME');
            $password = env('VENDOR_PRF_API_PASSWORD');

            $lastSuccessSync = Sync_log::where('status', 'success')
                ->orderByDesc('last_sync_at')
                ->first();

            $dateFrom = $lastSuccessSync && $lastSuccessSync->last_sync_at
                ? Carbon::parse($lastSuccessSync->last_sync_at)->subMinute()->toIso8601String()
                : '1970-01-01T00:00:00.000Z';

            $loginResponse = Http::timeout(60)->post($baseUrl . '/api/auth/login/v1', [
                'username' => $username,
                'password' => $password,
            ]);

            if (!$loginResponse->successful()) {
                $this->writeLog($syncStartedAt, 'failed', 0, 'Login ke API vendor gagal.');

                return [
                    'success' => false,
                    'message' => 'Login ke API vendor gagal.',
                    'total_api' => 0,
                    'total_sync' => 0,
                    'skip_nama_kosong' => 0,
                ];
            }

            $token = $loginResponse->json()['data'] ?? null;

            if (!$token) {
                $this->writeLog($syncStartedAt, 'failed', 0, 'Token API tidak ditemukan.');

                return [
                    'success' => false,
                    'message' => 'Token API tidak ditemukan.',
                    'total_api' => 0,
                    'total_sync' => 0,
                    'skip_nama_kosong' => 0,
                ];
            }

            $vendorResponse = Http::timeout(120)
                ->withToken($token)
                ->get($baseUrl . "/api/vendor/list/v1/{$dateFrom}");

            if (!$vendorResponse->successful()) {
                $this->writeLog($syncStartedAt, 'failed', 0, 'Gagal mengambil data vendor dari API.');

                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data vendor dari API.',
                    'total_api' => 0,
                    'total_sync' => 0,
                    'skip_nama_kosong' => 0,
                ];
            }

            $vendors = $vendorResponse->json()['data'] ?? [];

            if (!is_array($vendors)) {
                $this->writeLog($syncStartedAt, 'failed', 0, 'Format data vendor tidak valid.');

                return [
                    'success' => false,
                    'message' => 'Format data vendor tidak valid.',
                    'total_api' => 0,
                    'total_sync' => 0,
                    'skip_nama_kosong' => 0,
                ];
            }

            $totalApi = count($vendors);
            $totalSync = 0;
            $skippedNamaKosong = 0;

            foreach ($vendors as $vendor) {
                $nama = trim($vendor['vendor_name'] ?? '');
                $vendorAccount = trim($vendor['vendor_account'] ?? '');
                $termOfPayment = trim($vendor['term_of_payment'] ?? '');
                $status = trim($vendor['status'] ?? '');

                if ($nama === '') {
                    $skippedNamaKosong++;
                    continue;
                }

                $valid = strtolower($status) === 'registered' ? 1 : 0;

                $top = strtolower($termOfPayment);

                if (in_array($top, ['cod', 'dp'])) {
                    $hari = 0;
                } else {
                    preg_match('/\d+/', $top, $matches);
                    $hari = isset($matches[0]) ? (int) $matches[0] : 0;
                }

                foreach ($types as $type) {
                    if ($vendorAccount !== '') {
                        Payableto::updateOrCreate(
                            [
                                'nama' => $nama,
                                'type' => $type,
                                'vendor_account' => $vendorAccount,
                            ],
                            [
                                'term_payment' => $termOfPayment,
                                'hari' => $hari,
                                'valid' => $valid,
                                'user_entry' => $userEntry,
                            ]
                        );
                    } else {
                        $existing = Payableto::where('nama', $nama)
                            ->where('type', $type)
                            ->where(function ($query) {
                                $query->whereNull('vendor_account')
                                    ->orWhere('vendor_account', '');
                            })
                            ->first();

                        if ($existing) {
                            $existing->update([
                                'term_payment' => $termOfPayment,
                                'hari' => $hari,
                                'valid' => $valid,
                                'user_entry' => $userEntry,
                            ]);
                        } else {
                            Payableto::create([
                                'nama' => $nama,
                                'type' => $type,
                                'vendor_account' => null,
                                'term_payment' => $termOfPayment,
                                'hari' => $hari,
                                'valid' => $valid,
                                'user_entry' => $userEntry,
                            ]);
                        }
                    }
                }

                $totalSync++;
            }

            $message = "Sync berhasil. Total Data API: {$totalApi}, diproses: {$totalSync}, diskip karena nama kosong: {$skippedNamaKosong}";

            $this->writeLog($syncStartedAt, 'success', $totalSync, $message);

            return [
                'success' => true,
                'message' => $message,
                'total_api' => $totalApi,
                'total_sync' => $totalSync,
                'skip_nama_kosong' => $skippedNamaKosong,
                'date_from' => $dateFrom,
            ];
        } catch (\Throwable $e) {
            Log::error('Sync payable error: ' . $e->getMessage());

            $this->writeLog(now(), 'failed', 0, $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi error saat sync data vendor.',
                'total_api' => 0,
                'total_sync' => 0,
                'skip_nama_kosong' => 0,
            ];
        }
    }

    private function writeLog($lastSyncAt, string $status, int $totalData, string $message): void
    {
        Sync_log::create([
            'last_sync_at' => $lastSyncAt,
            'status' => $status,
            'total_data' => $totalData,
            'message' => $message,
        ]);
    }
}
