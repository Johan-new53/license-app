<?php

namespace App\Jobs;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class SendGraphMail implements ShouldQueue
{
    use Queueable;
    public $tries = 3;      // 🔥 coba kirim ulang 3x kalau gagal
    public $backoff = 10;   // 🔥 jeda 10 detik antar retry
    protected $to;
    protected $subject;
    protected $body;
    protected $tenantKey;
    protected $from;
   
    
    public function __construct($to, $subject, $body, $tenantKey, $from)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->tenantKey = $tenantKey;
        $this->from = $from;
    }
        

    public function handle()
    {
        if (!$this->tenantKey) {
            throw new \Exception('TenantKey kosong!');
        }
        $config = config("graph.tenants.{$this->tenantKey}");

        if (!$config) {
            throw new \Exception('Tenant config not found');
        }
        
        $tenantId = $config['tenant_id'];
        $clientId = $config['client_id'];
        $clientSecret = $config['client_secret'];

        $tokenUrl = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";

        // 🔥 TARUH DI SINI (cache token)
        $cacheKey = "graph_token_{$tenantId}";

        $accessToken = Cache::remember($cacheKey, 3500, function () use ($tokenUrl, $clientId, $clientSecret) {
            $response = Http::asForm()->post($tokenUrl, [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ]);

            return $response['access_token'];
        });
        
        // ✅ kirim email
      $response = Http::withToken($accessToken)
        ->post("https://graph.microsoft.com/v1.0/users/{$this->from}/sendMail", [
            'message' => [
                'subject' => $this->subject,
                'body' => [
                    'contentType' => 'HTML',
                    'content' => $this->body,
                ],
                'toRecipients' => [
                    [
                        'emailAddress' => [
                            'address' => $this->to,
                        ],
                    ],
                ],
            ],
            'saveToSentItems' => true 
        ]);

   

    }
        
}