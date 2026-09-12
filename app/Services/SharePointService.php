<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SharePointService
{
    private string $graphUrl = 'https://graph.microsoft.com/v1.0';

    /**
     * Get Microsoft Graph access token
     */
    private function getAccessToken(): string
    {
        $response = Http::asForm()->post(
            'https://login.microsoftonline.com/'
            . config('sharepoint.tenant_id')
            . '/oauth2/v2.0/token',
            [
                'client_id' => config('sharepoint.client_id'),
                'client_secret' => config('sharepoint.client_secret'),
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ]
        );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Gagal mendapatkan Microsoft Graph access token: '
                . $response->body()
            );
        }

        return $response->json('access_token');
    }

    /**
     * Get SharePoint Site ID
     */
    private function getSiteId(string $accessToken): string
    {
        $hostname = config('sharepoint.hostname');
        $sitePath = config('sharepoint.site_path');

        $response = Http::withToken($accessToken)
            ->get(
                $this->graphUrl
                . '/sites/'
                . $hostname
                . ':'
                . $sitePath
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Gagal mendapatkan SharePoint Site ID: '
                . $response->body()
            );
        }

        return $response->json('id');
    }

    /**
     * Get default document library (Drive)
     */
    private function getDriveId(
        string $accessToken,
        string $siteId
    ): string {

        $response = Http::withToken($accessToken)
            ->get(
                $this->graphUrl
                . '/sites/'
                . $siteId
                . '/drive'
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Gagal mendapatkan SharePoint Drive ID: '
                . $response->body()
            );
        }

        return $response->json('id');
    }

    /**
     * Upload file to SharePoint
     */
    public function upload(
        string $localFilePath,
        string $filename
    ): array {

        $accessToken = $this->getAccessToken();

        $siteId = $this->getSiteId($accessToken);

        $driveId = $this->getDriveId(
            $accessToken,
            $siteId
        );

        $folder = trim(
            config('sharepoint.folder'),
            '/'
        );

        $uploadUrl =
            $this->graphUrl
            . '/drives/'
            . $driveId
            . '/root:/'
            . $folder
            . '/'
            . rawurlencode($filename)
            . ':/content';

        $fileContent = file_get_contents($localFilePath);

        $response = Http::withToken($accessToken)
            ->withBody(
                $fileContent,
                'application/pdf'
            )
            ->put($uploadUrl);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Gagal upload file ke SharePoint: '
                . $response->body()
            );
        }

        return $response->json();
    }
}
