<?php

namespace Ronmrcdo\Inventory\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Ronmrcdo\Inventory\Exceptions\InvalidAttributeException;

trait HasAttributes
{
	/**
	 * Create a product attribute
	 * 
	 * @param array $attributeData
	 * @return void
	 */
	public function createAttribute(array $attributeData)
	{
		$attribute = $this->attributes()->create($attributeData);

		if (! $attribute) {
			throw new InvalidAttributeException("Invalid attribute", 422);
		}

		return $this;
	}

	/**
	 * Assert if the Product has attributes
	 * 
	 * @return bool
	 */
	public function hasAttributes(): bool
	{
		return !! $this->attributes()->count();
	}

	/**
	 * Relation on Attribute Model
	 * 
	 * @return HasMany
	 */
	public function attributes(): HasMany
	{
		return $this->hasMany('Ronmrcdo\Inventory\Models\Attribute');
	}
}