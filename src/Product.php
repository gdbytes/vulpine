<?php

namespace Vulpine;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class Product extends Model
{
    use Eloquence, Mappable;
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
     * Define mappings on the model.
     *
     * @var array
     */
    protected $maps = [

    ];

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
}