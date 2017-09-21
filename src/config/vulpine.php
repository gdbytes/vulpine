<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Vulpine Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Configure your database connection in `config/database.php` and then set
    | its name, like 'vulpine' or 'whmcs', for example.
    |
    */
    'connection' => 'whmcs',

    /*
    |--------------------------------------------------------------------------
    | WHMCS API Specific Config
    |--------------------------------------------------------------------------
    |
    | If you would like to interact with the WHMCS API from within your
    | application you can configure it here.
    |
    */
    'whmcs' => [
        /**
         * Full URL Full to your WHMCS API. It includes with /includes/api.php location.
         */
        'api_url' => env('WHMCS_API_URL', ''),

        /**
         * Add your WHMCS identifier and secret key.
         * You can learn more about creating API credentials at the link below.
         *
         * Note: You can use your WHMCS admin username (WHMCS_IDENTIFIER) and
         * password (WHMCS_SECRET). However, as this is not recommended and could
         * soon be deprecated you should use the API key pair.
         *
         * @link https://developers.whmcs.com/api/authentication/
         */
        'identifier'	=>	env('WHMCS_IDENTIFIER', ''),
        'secret'	=>	env('WHMCS_SECRET', ''),

        /**
         * Configure the format in which you would like response data to be sent in.
         * 'json' is the recommended/default format. 'xml' is also supported.
         */
        'response'=> 'json' // json or xml

    ],
];