<?php

return [
    'tenant_id' => env('MICROSOFT_TENANT_ID'),
    'client_id' => env('MICROSOFT_CLIENT_ID'),
    'client_secret' => env('MICROSOFT_CLIENT_SECRET'),

    'hostname' => env(
        'MS_SHAREPOINT_HOSTNAME',
        'siloamhospitals.sharepoint.com'
    ),

    'site_path' => env(
        'MS_SHAREPOINT_SITE_PATH',
        '/sites/HeadOfficeAPTeam'
    ),

    'folder' => env(
        'MS_SHAREPOINT_FOLDER',
        'PRF_BARU'
    ),

];
