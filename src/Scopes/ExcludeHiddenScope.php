<?php

namespace Vulpine\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExcludeHiddenScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (property_exists($model, 'excludeHidden') && $model->excludeHidden) {
            $column = $model->getHiddenColumn();
            $builder->where($column, 0);
        }
    }
}