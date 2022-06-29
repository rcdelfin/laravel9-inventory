<?php

namespace Rcdelfin\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Rcdelfin\Inventory\Traits\Sluggable;
use Rcdelfin\Inventory\Traits\HasAttributes;
use Rcdelfin\Inventory\Traits\HasVariants;
use Rcdelfin\Inventory\Traits\HasCategories;
use Rcdelfin\Inventory\Traits\HasInventory;


class Product extends Model
{
	use HasAttributes, HasVariants, Sluggable, HasCategories, 
		HasInventory;

	/**
	 * Defined table name
	 * 
	 * @var string
	 */
	protected $table = 'products';

	/**
	 * Fields that are mass assignable
	 * 
	 * @var array
	 */
	protected $fillable = [
		'name', 'short_description', 'description',
		'category_id', 'is_active'
	];

	/**
	 * Guarded Fields
	 * 
	 * @var array
	 */
	protected $guarded = [
		'id', 'created_at', 'updated_at'
	];

	/**
	 * Fields that should be casted on another
	 * type
	 * 
	 * @var array
	 */
	protected $casts = [
		'is_active' => 'boolean'
	];

	/**
	 * Sluggable field of the model
	 * 
	 * @var string
	 */
	protected $sluggable = 'name';
}