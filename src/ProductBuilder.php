<?php

namespace Vulpine;

use Illuminate\Database\Eloquent\Builder;

class ProductBuilder extends Builder
{
    /**
     * Limit products to a specific type.
     *
     * @param $productType
     * @return $this
     */
    public function type($productType)
    {
        return $this->where('type', $productType);
    }

    /**
     * Show only hosting account products.
     *
     * @return ProductBuilder
     */
    public function hostingAccounts()
    {
        return $this->type('hostingaccount');
    }

    /**
     * Show only reseller account products.
     *
     * @return ProductBuilder
     */
    public function resellerAccount()
    {
        return $this->type('reselleraccount');
    }

    /**
     * Show only server account products.
     *
     * @return ProductBuilder
     */
    public function servers()
    {
        return $this->type('server');
    }

    /**
     * Show only 'other' products (SSL certs, etc...).
     *
     * @return ProductBuilder
     */
    public function other()
    {
        return $this->type('other');
    }
}

