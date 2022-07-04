<?php

namespace Rcdelfin\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeValue extends Model
{
    /**
     * Table name of the attribute values
     *
     * @var string
     */
    protected $table = 'product_attribute_values';

    /**
     * Disable the timestamp on model creation
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_attribute_id', 'value'
    ];

    /**
     * Fields that can't be assigned
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Product Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo $this
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo('Rcdelfin\Inventory\Models\Attribute', 'product_attribute_id');
    }

    /**
     * Relation of the attribute option to the variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany $this
     */
    public function variations(): HasMany
    {
        return $this->hasMany('Rcdelfin\Inventory\Models\ProductVariant');
    }
}
