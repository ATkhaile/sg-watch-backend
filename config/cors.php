<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // local
        // 'http://localhost:3000',
        // 'http://localhost:3001',
        // 'http://localhost:3002',
        // 'http://app.elc-dev.local',
        // 'http://admin.elc-dev.local',

        // // legacy admin demo
        // 'http://elemental-cloud-frontend.demo-dev.xyz',
        // 'https://elemental-cloud-frontend.demo-dev.xyz',

        // // ELC Commons
        // 'http://elc-admin.demo-dev.xyz',
        // 'https://elc-admin.demo-dev.xyz',
        // 'http://elc-community.demo-dev.xyz',
        // 'https://elc-community.demo-dev.xyz',
        // 'http://elc-staffless-franchise.demo-dev.xyz',
        // 'https://elc-staffless-franchise.demo-dev.xyz',

        // // TDBS
        // 'http://elc-tdbs-dev-app.demo-dev.xyz',
        // 'https://elc-tdbs-dev-app.demo-dev.xyz',

        // // TERAKONA
        // 'http://elc-terakona-dev-app.demo-dev.xyz',
        // 'https://elc-terakona-dev-app.demo-dev.xyz',
        // 'http://terakona.hair',
        // 'https://terakona.hair',

        // // happi mum
        // 'http://elc-zuruoya-dev-app.demo-dev.xyz',
        // 'https://elc-zuruoya-dev-app.demo-dev.xyz',
        // 'http://elc-zuruoya-dev-admin.demo-dev.xyz',
        // 'https://elc-zuruoya-dev-admin.demo-dev.xyz',
        // 'http://happimum.com',
        // 'https://happimum.com',

        // // Game Community
        // 'http://elc-gameagelayer-dev-admin.demo-dev.xyz',
        // 'https://elc-gameagelayer-dev-admin.demo-dev.xyz',
        // 'http://elc-gameagelayer-dev-app.demo-dev.xyz',
        // 'https://elc-gameagelayer-dev-app.demo-dev.xyz',
        '*' // check cors domain database 
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
