<?php

namespace Vulpine;

class Product extends Model
{
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
    protected $with = ['pricing', 'group'];

    /**
     * Show hidden items as default.
     *
     * @var bool
     */
    protected $excludeHidden = false;

    /**
     * Get the pricing for the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricing()
    {
        return $this->hasMany(Price::class, 'relid')->where('type', 'product');
    }

    /**
     * Get the group associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne(ProductGroup::class, 'id', 'gid');
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
     * Overriding newQuery() to the custom ProductBuilder.
     *
     * @return ProductBuilder
     */
    public function newQuery()
    {
        $builder = new ProductBuilder($this->newBaseQueryBuilder());
        $builder->setModel($this)->with($this->with);

        if (isset($this->excludeHidden) && $this->excludeHidden == true) {
            $builder->where('hidden', 0);
        }

        return $builder;
    }
}