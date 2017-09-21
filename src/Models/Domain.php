<?php

namespace Vulpine\Models;

class Domain extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbldomainpricing';

    /**
     * Eager load relationships.
     *
     * @var array
     */
    protected $with = ['pricing'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Map better field names to WHMCS pricing fields.
     *
     * @var array
     */
    protected static $mapPricing = [
        'year1' => 'msetupfee',
        'year2' => 'qsetupfee',
        'year3' => 'ssetupfee',
        'year4' => 'asetupfee',
        'year5' => 'bsetupfee',
    ];

    /**
     * Map better field names to WHMCS field names.
     *
     * @var array
     */
    protected static $mapTypes = [
        'register' => 'domainregister',
        'transfer' => 'domaintransfer',
        'renew'    => 'domainrenew'
    ];

    /**
     * Get the pricing for the domain.
     *
     * @return $this
     */
    public function pricing()
    {
        return $this->hasMany(Price::class, 'relid')->whereIn('type', [
            'domainrenew', 'domainregister', 'domaintransfer'
        ]);
    }

    /**
     * Loop through the domain prices and grab the relevant registration price.
     *
     * @param        $year
     * @param string $type
     *
     * @return mixed
     */
    public function getDomainPrice($year, $type = 'register')
    {
        foreach ($this->pricing as $price) {
            if ($price['type'] === self::$mapTypes[$type]) {
                return $price[self::$mapPricing[$year]];
            }
        }
    }
}