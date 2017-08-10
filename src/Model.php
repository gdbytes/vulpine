<?php

namespace Vulpine;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Model extends Eloquent
{
    /**
     * Model constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->configureDatabaseConnection();
    }

    /**
     * Get the hidden column name.
     *
     * @return mixed|string
     */
    public function getHiddenColumn()
    {
        return property_exists($this, 'hiddenColumn') ? $this->hiddenColumn : 'hidden';
    }

    /**
     * Auto configure database connection for Laravel users.
     *
     * @return void
     */
    protected function configureDatabaseConnection()
    {
        if (defined('LARAVEL_START') && function_exists('config')) {
            if ($connection = config('vulpine.connection')) {
                $this->connection = $connection;
            } elseif (config('database.connections.vulpine')) {
                $this->connection = 'vulpine';
            } elseif (config('database.connections.whmcs')) {
                $this->connection = 'whmcs';
            }
        }
    }
}