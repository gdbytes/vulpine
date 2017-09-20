<?php

namespace Vulpine;

class Price extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblpricing';

    /**
     * A pricing entry belongs to a product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * A pricing entry belongs to a domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}