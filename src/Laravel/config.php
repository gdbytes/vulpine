<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Vulpine Database Connection Name
    |--------------------------------------------------------------------------
    |
    | By default, Vulpine uses your default database connection, set on
    | `config/database.php` (`default` key). Usually you'd like to use a
    | custom database just for WordPress. First you must configure that
    | database connection in `config/database.php`, and then set here its
    | name, like 'vulpine', for example. Then you can work with two or more
    | database, but this one is only for your WordPress tables.
    |
    */
    'connection' => 'whmcs',

    /**
     * TODO: delete
     * Used for formatting currencies.
     * You can find your locale here: https://msdn.microsoft.com/en-us/library/ee825488%28v=cs.20%29.aspx?f=255&MSPPError=-2147217396
     */
    'locale' => 'en_GB'
];