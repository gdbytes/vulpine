<?php

namespace Vulpine;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Model extends Eloquent
{
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
     * Define a one-to-one relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        if ($instance instanceof self) {
            $instance->setConnection($this->getConnection()->getName());
        } else {
            $instance->setConnection($instance->getConnection()->getName());
        }

        $localKey = $localKey ?: $this->getKeyName();

        return new HasOne($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

    /**
     * Define a one-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        if ($instance instanceof self) {
            $instance->setConnection($this->getConnection()->getName());
        } else {
            $instance->setConnection($instance->getConnection()->getName());
        }

        $localKey = $localKey ?: $this->getKeyName();

        return new HasMany(
            $instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey
        );
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $ownerKey
     * @param  string  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        // If no relation name was given, we will use this debug backtrace to extract
        // the calling method's name and use that as the relationship name as most
        // of the time this will be what we desire to use for the relationships.
        if (is_null($relation)) {
            $relation = $this->guessBelongsToRelation();
        }

        $instance = $this->newRelatedInstance($related);

        // If no foreign key was supplied, we can use a backtrace to guess the proper
        // foreign key name by using the name of the relationship function, which
        // when combined with an "_id" should conventionally match the columns.
        if (is_null($foreignKey)) {
            $foreignKey = Str::snake($relation).'_'.$instance->getKeyName();
        }

        if ($instance instanceof self) {
            $instance->setConnection($this->getConnection()->getName());
        } else {
            $instance->setConnection($instance->getConnection()->getName());
        }

        // Once we have the foreign key names, we'll just create a new Eloquent query
        // for the related models and returns the relationship instance which will
        // actually be responsible for retrieving and hydrating every relations.
        $ownerKey = $ownerKey ?: $instance->getKeyName();

        return new BelongsTo(
            $instance->newQuery(), $this, $foreignKey, $ownerKey, $relation
        );
    }

    /**
     * Get the relation value setting the connection name.
     *
     * @param string $key
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
     * @return string
     */
    public function getConnectionName()
    {
        if (!isset($this->connection) && (function_exists('app') && app() instanceof Application)) {
            if ($connection = config('vulpine.connection')) {
                $this->connection = $connection;
            }
        }

        return $this->connection;
    }
}