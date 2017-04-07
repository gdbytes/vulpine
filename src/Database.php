<?php

namespace Vulpine;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    /**
     * Default base parameters.
     *
     * @var array
     */
    protected static $baseParams = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'charset' => 'utf8',
        'collation' => 'latin1_swedish_ci',
        'prefix' => ''
    ];

    /**
     * Connect to the WHMCS database.
     *
     * @param array $params
     * @return Capsule
     */
    public static function connect(array $params)
    {
        $capsule = new Capsule();
        $params = array_merge(static::$baseParams, $params);
        $capsule->addConnection($params);
        $capsule->bootEloquent();

        return $capsule;
    }
}