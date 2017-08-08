<?php

namespace Vulpine;

use Vulpine\Traits\ExcludeHidden;

class ProductGroup extends Model
{
    use ExcludeHidden;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblproductgroups';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Hide hidden items as default.
     *
     * @var bool
     */
    public $excludeHidden = true;

    /**
     * A group has many products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'gid');
    }
}