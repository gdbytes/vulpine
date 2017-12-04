<?php

namespace Vulpine\Models;

use Illuminate\Database\Query\Builder;

class Currency extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblcurrencies';

    /**
     * Query scope to get the default currency.
     *
     * @param Builder $query
     *
     * @return Builder $query
     */
    public function scopeDefault(Builder $query)
    {
        return $query->where('default', 1);
    }
}