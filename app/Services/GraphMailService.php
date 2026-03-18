<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GraphMailService
{
    private function getToken()
    {
        return Cache::remember('graph_token', 3500, function () {

            if (auth()->id() == 12) {
            $response = Http::asForm()->post(
                "https://login.microsoftonline.com/".env('GRAPH_TENANT_ID')."/oauth2/v2.0/token",
                [
                    'client_id' => env('GRAPH_CLIENT_ID'),
                    'client_secret' => env('GRAPH_CLIENT_SECRET'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'https://graph.microsoft.com/.default'
                ]
            );
            }
            elseif (auth()->id() == 13) {
            $response = Http::asForm()->post(
                "https://login.microsoftonline.com/".env('GRAPH_TENANT_ID2')."/oauth2/v2.0/token",
                [
                    'client_id' => env('GRAPH_CLIENT_ID2'),
                    'client_secret' => env('GRAPH_CLIENT_SECRET2'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'https://graph.microsoft.com/.default'
                ]
            );
            }
            elseif (auth()->id() == 16) {
            $response = Http::asForm()->post(
                "https://login.microsoftonline.com/".env('GRAPH_TENANT_ID3')."/oauth2/v2.0/token",
                [
                    'client_id' => env('GRAPH_CLIENT_ID3'),
                    'client_secret' => env('GRAPH_CLIENT_SECRET3'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'https://graph.microsoft.com/.default'
                ]
            );
            }
            elseif (auth()->id() == 17) {
            $response = Http::asForm()->post(
                "https://login.microsoftonline.com/".env('GRAPH_TENANT_ID4')."/oauth2/v2.0/token",
                [
                    'client_id' => env('GRAPH_CLIENT_ID4'),
                    'client_secret' => env('GRAPH_CLIENT_SECRET4'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'https://graph.microsoft.com/.default'
                ]
            );
            }


            return $response['access_token'];
        });
    }

    public function send($to,$subject,$html)
    {
        $token = $this->getToken();

        if (auth()->id() == 12) {
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
        elseif (auth()->id() == 13) {
        Http::withToken($token)->post(
            "https://graph.microsoft.com/v1.0/users/".env('GRAPH_SENDER2')."/sendMail",
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
        elseif (auth()->id() == 16) {
        Http::withToken($token)->post(
            "https://graph.microsoft.com/v1.0/users/".env('GRAPH_SENDER3')."/sendMail",
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
        elseif (auth()->id() == 17) {
        Http::withToken($token)->post(
            "https://graph.microsoft.com/v1.0/users/".env('GRAPH_SENDER4')."/sendMail",
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
}