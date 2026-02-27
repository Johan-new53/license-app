<?php

namespace App\Services;

use App\Models\Finance;

class DocNoCheckService
{
    private const FILTER_MAP = [
        'id_dept'       => 'id_dept',
        'id_rek_sumber' => 'id_rek_sumber',
        'id_payable_h'  => 'id_payable_h',
        'id_bank'       => 'id_bank',
        'id_currency'   => 'id_currency',
    ];

    public function check(
        string $input,
        ?string $documentType = 'all',
        ?int $ignoreId = null,
        ?string $filterField = null,
        $filterValue = null
    ): array {
        $tokens = collect(explode(',', $input))
            ->map(fn($v) => trim($v))
            ->filter()
            ->values();

        if ($tokens->isEmpty()) {
            return ['exists' => [], 'clean' => []];
        }

        // duplicate input
        $duplicates = [];
        foreach ($tokens as $value) {
            if ($tokens->filter(fn($v) => $v === $value)->count() > 1) {
                $duplicates[] = $value;
            }
        }
        $duplicates = array_unique($duplicates);

        $clean = $tokens->unique()->values()->all();

        $exists = [];
        foreach ($duplicates as $dup) {
            $exists[$dup] = true;
        }

        $q = Finance::query();

        if ($documentType && $documentType !== 'all') {
            $q->where('type', $documentType);
        }

        if (!is_null($ignoreId)) {
            $q->where('id', '!=', $ignoreId);
        }

        // ✅ filter tambahan (opsional)
        if ($filterField && isset(self::FILTER_MAP[$filterField])) {

            $dbColumn = self::FILTER_MAP[$filterField];

            if ($filterValue !== null && $filterValue !== '') {
                $q->where($dbColumn, $filterValue);
            }
        }

        // cek DB doc_no
        $q->where(function ($sub) use ($clean) {
            foreach ($clean as $n) {
                $safe = preg_quote($n, '/');
                $sub->orWhereRaw("doc_no REGEXP ?", ["(^|,){$safe}(,|$)"]);
            }
        });

        $rows = $q
            ->with(['creator:id,name'])
            ->get(['id', 'doc_no', 'user_entry']);

        $makers = [];

        foreach ($rows as $row) {
            foreach (explode(',', (string) $row->doc_no) as $t) {
                $t = trim($t);

                if (in_array($t, $clean, true)) {
                    $exists[$t] = true;

                    $makers[$t][] = [
                        'finance_id' => $row->id,
                        'user_name'  => $row->creator->name ?? '-',
                    ];
                }
            }
        }

        return [
            'exists' => array_keys($exists),
            'clean'  => $clean,
            'makers' => $makers,
        ];
    }
}
