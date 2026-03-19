<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GraphMailService
{
    private function getToken()
    {
        return Cache::remember('graph_token', 3500, function () {
            
            $response = Http::asForm()->post(
                "https://login.microsoftonline.com/".env('GRAPH_TENANT_ID')."/oauth2/v2.0/token",
                [
                    'client_id' => env('GRAPH_CLIENT_ID'),
                    'client_secret' => env('GRAPH_CLIENT_SECRET'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'https://graph.microsoft.com/.default'
                ]
            );                        

            return $response['access_token'];
        });
    }

    public function send($to,$subject,$html)
    {
        $token = $this->getToken();
        
        Http::withToken($token)->post(
            "https://graph.microsoft.com/v1.0/users/".env('GRAPH_SENDER')."/sendMail",
            [
                "message"=>[
                    "subject"=>$subject,
                    "body"=>[
                        "contentType"=>"HTML",
                        "content"=>$html
                    ],
                    "toRecipients"=>[
                        [
                            "emailAddress"=>[
                                "address"=>$to
                            ]
                        ]
                    ]
                ],
                "saveToSentItems"=>true
            ]
        );
        
       


    }
}