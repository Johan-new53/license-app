<?php

return [
    'tenants' => [
        'tenant1' => [
            'tenant_id' => env('GRAPH_TENANT_ID'),
            'client_id' => env('GRAPH_CLIENT_ID'),
            'client_secret' => env('GRAPH_CLIENT_SECRET'),
        ],
        'tenant2' => [
            'tenant_id' => env('GRAPH_TENANT_ID2'),
            'client_id' => env('GRAPH_CLIENT_ID2'),
            'client_secret' => env('GRAPH_CLIENT_SECRET2'),
        ],
        'tenant3' => [
            'tenant_id' => env('GRAPH_TENANT_ID3'),
            'client_id' => env('GRAPH_CLIENT_ID3'),
            'client_secret' => env('GRAPH_CLIENT_SECRET3'),
        ],
        'tenant4' => [
            'tenant_id' => env('GRAPH_TENANT_ID4'),
            'client_id' => env('GRAPH_CLIENT_ID4'),
            'client_secret' => env('GRAPH_CLIENT_SECRET4'),
        ],
    ],
];