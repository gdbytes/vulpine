<?php

namespace Vulpine;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Model extends Eloquent
{
    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->configureDatabaseConnection();
    }

    /**
     * Define a one-to-one relationship.
     * No longer necessary for Laravel >=5.4 - keeping for backwards compatibility.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $instance = new $related;
        if (! $instance->getConnectionName()) {
            $instance->setConnection($this->connection);
        }
        $localKey = $localKey ?: $this->getKeyName();
        return new HasOne($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

    /**
     * Define a one-to-many relationship.
     * No longer necessary for Laravel >=5.4 - keeping for backwards compatibility.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $instance = new $related;
        if (! $instance->getConnectionName()) {
            $instance->setConnection($this->connection);
        }
        $localKey = $localKey ?: $this->getKeyName();
        return new HasMany($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

    /**
     * Get the relation value setting the connection name.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getRelationValue($key)
    {
        $relation = parent::getRelationValue($key);
        if ($relation instanceof Collection) {
            $relation->each(function ($model) {
                $this->setRelationConnection($model);
            });
            return $relation;
        }
        $this->setRelationConnection($relation);
        return $relation;
    }
    /**
     * Set the connection name to model.
     *
     * @param $model
     */
    protected function setRelationConnection($model)
    {
        if ($model instanceof Eloquent) {
            $model->setConnection($this->getConnectionName());
        }
    }

    /**
     * Auto configure database connection for Laravel users.
     *
     * @return void
     */
    protected function configureDatabaseConnection()
    {
        if (defined('LARAVEL_START') and function_exists('config')) {
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