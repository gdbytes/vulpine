<?php

namespace Vulpine;

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
     * @param $query
     *
     * @return mixed
     */
    public function scopeDefault($query)
    {
        return $query->where('default', 1);
    }
}