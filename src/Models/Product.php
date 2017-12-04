<?php

namespace Vulpine\Models;

use Illuminate\Database\Query\Builder;
use Vulpine\Traits\ExcludeHidden;

class Product extends Model
{
    use ExcludeHidden;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblproducts';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Eager load relationships.
     *
     * @var array
     */
    protected $with = ['pricing'];

    /**
     * Hide hidden items as default.
     *
     * @var bool
     */
    public $excludeHidden = true;

    /**
     * Get the pricing for the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne HasOne
     */
    public function pricing()
    {
        return $this->hasOne(Price::class, 'relid')->where('type', 'product');
    }

    /**
     * A product belongs to a gro
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(ProductGroup::class);
    }

    /**
     * Scope to limit products to a specific type.
     *
     * @param $query
     * @param $productType
     *
     * @return Builder $query
     */
    public function scopeType(Builder $query, $productType)
    {
        return $query->where('type', $productType);
    }

    /**
     * Show only hosting account products.
     *
     * @param $query
     *
     * @return Builder $query
     */
    public function scopeHostingAccounts(Builder $query)
    {
        return $this->scopeType($query, 'hostingaccount');
    }

    /**
     * Show only reseller account products.
     *
     * @param $query
     *
     * @return Builder $query
     */
    public function scopeResellerAccount(Builder $query)
    {
        return $this->scopeType($query, 'reselleraccount');
    }

    /**
     * Show only server account products.
     *
     * @param $query
     *
     * @return Builder $query
     */
    public function scopeServers(Builder $query)
    {
        return $this->scopeType($query, 'server');
    }

    /**
     * Show only 'other' products (SSL certs, etc...).
     *
     * @param $query
     *
     * @return Builder $query
     */
    public function scopeOther(Builder $query)
    {
        return $this->scopeType($query, 'other');
    }

    /**
     * Does product offer a free domain.
     *
     * @return bool
     */
    public function hasFreeDomain()
    {
        return !empty($this->freedomain) ? true : false;
    }

    /**
     * Is the product a featured product.
     *
     * @return bool
     */
    public function isFeatured()
    {
        return !empty($this->is_featured) ? true : false;
    }

    /**
     *  Get the package name.
     *
     * @return string
     */
    public function getPackageNameAttribute()
    {
        return $this->configoption1;
    }

    /**
     * Get product disk space.
     *
     * @return string
     */
    public function getDiskSpaceAttribute()
    {
        if (!empty($this->configoption3)) {
            return format_megabytes($this->configoption3);
        }

        return $this->configoption3;

    }

    /**
     * Get no # of email accounts for product.
     *
     * @return string
     */
    public function getEmailAccountsAttribute()
    {
        return $this->configoption4;
    }

    /**
     * Get product bandwidth.
     *
     * @return string
     */
    public function getBandwidthAttribute()
    {
        if (!empty($this->configoption5)) {
            return format_megabytes($this->configoption5);
        }

        return $this->configoption5;

    }

    /**
     * Get no # of databases for product.
     *
     * @return string
     */
    public function getDatabasesAttribute()
    {
        return $this->configoption8;
    }

    /**
     * Get no # of addon sites for product.
     *
     * @return string
     */
    public function getAddonSitesAttribute()
    {
        return $this->configoption14;
    }

    /**
     * Get product reseller disk space.
     *
     * @return string
     */
    public function getResellerDiskSpaceAttribute()
    {
        if (!empty($this->configoption17)) {
            return format_megabytes($this->configoption17);
        }

        return $this->configoption17;
    }

    /**
     * Get product reseller bandwidth.
     *
     * @return string
     */
    public function getResellerBandwidthAttribute()
    {
        if (!empty($this->configoption18)) {
            return format_megabytes($this->configoption18);
        }

        return $this->configoption18;
    }

    /**
     * Get product reseller account limits.
     *
     * @return mixed
     */
    public function getResellerAccountLimitAttribute()
    {
        return $this->configoption15;
    }

    /**
     * Get product free domain TLDs.
     *
     * @return array
     */
    public function freeDomainExtensions()
    {
        return !empty($this->freedomaintlds) ? explode(',', $this->freedomaintlds) : [];
    }

    /**
     * Get pricing for a given frequency/payment term.
     *
     * @param $paymentTerms
     *
     * @return string
     */
    public function getPricing($paymentTerms)
    {
        return $this->pricing->$paymentTerms;
    }

    /**
     * Get setup fee for given frequency/payment term.
     *
     * @param $paymentTerms
     *
     * @return string
     */
    public function getSetupFee($paymentTerms)
    {
        $column = $paymentTerms[0] . 'setupfee';

        return $this->pricing->$column;
    }
}