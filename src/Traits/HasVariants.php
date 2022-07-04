<?php

namespace Rcdelfin\Inventory\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rcdelfin\Inventory\Exceptions\InvalidVariantException;
use Rcdelfin\Inventory\Exceptions\InvalidAttributeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Rcdelfin\Inventory\Models\ProductVariant;
use Rcdelfin\Inventory\Models\ProductSku;

trait HasVariants
{
    /**
     * Add Variant to the product
     *
     * @param array $variant
     */
    public function addVariant($variant)
    {
        DB::beginTransaction();

        try {
            // if the give given variant array doesn't match the structure we want
            // it will automatically throw an InvalidVariant Exception
            // Verify if the given variant attributes already exist in the variants db
            if (in_array($this->sortAttributes($variant['variation']), $this->getVariants())) {
                throw new InvalidVariantException("Duplicate variation attributes!", 400);
            }

            // Create the sku first, so basically you can't add new attributes to the sku
            $sku = $this->skus()->create([
                'code' => $variant['sku'],
                'price' => $variant['price'],
                'cost' => $variant['cost']
            ]);

            foreach ($variant['variation'] as $item) {
                $attribute = $this->attributes()->where('name', $item['option'])->firstOrFail();
                $value = $attribute->values()->where('value', $item['value'])->firstOrFail();

                $this->variations()->create([
                    'product_sku_id' => $sku->id,
                    'product_attribute_id' => $attribute->id,
                    'product_attribute_value_id' => $value->id
                ]);
            }

            DB::commit();
        } catch (ModelNotFoundException $err) {
            DB::rollBack();

            throw new InvalidAttributeException($err->getMessage(), 404);
        } catch (\Throwable $err) {
            DB::rollBack();

            throw new InvalidVariantException($err->getMessage(), 400);
        }

        return $this;
    }

    /**
     * Get the variations
     *
     */
    public function getVariations()
    {
        return $this->skus;
    }

    /**
     * Get existing variants of the product
     * Note: There was a problem calling $this->variation relationship
     * it doesn't update model about the relationship that's why it always
     * return []
     *
     * @return array
     */
    protected function getVariants(): array
    {
        $variants = ProductVariant::where('product_id', $this->id)->get();

        return $this->transformVariant($variants);
    }

    /**
     * Sort the variant attributes by name. this is a helper function
     * to assert if the variant attributes already exist.
     *
     * @param array $variant
     * @return array
     */
    protected function sortAttributes($variant): array
    {
        return collect($variant)
            ->sortBy('option')
            ->map(function ($item) {
                return [
                    'option' => strtolower($item['option']),
                    'value' => strtolower($item['value'])
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Transform the variant to match it to the input
     * variant. To able to assert if the given new variant
     * already exist with the current variations
     *
     * @param \Rcdelfin\Inventory\Models\ProductVariant Array<$variants>
     * @return array
     */
    protected function transformVariant($variants): array
    {
        return collect($variants)
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'sku' => $item->productSku->code,
                    'attribute' => $item->attribute->name,
                    'option' => $item->option->value
                ];
            })
            ->keyBy('id')
            ->groupBy('sku')
            ->map(function ($item) {
                return collect($item)
                    ->map(function ($var) {
                        return [
                            'option' => strtolower($var['attribute']),
                            'value' => strtolower($var['option'])
                        ];
                    })
                    ->sortBy('option')
                    ->values()
                    ->toArray();
            })
            ->all();
    }

    /**
     * Assert if the product has any sku given in the db
     *
     * @return bool
     */
    public function hasSku(): bool
    {
        return !!$this->skus()->count();
    }

    /**
     * Static function that automatically query for the sku
     *
     * @param string $sku
     * @return \Rcdelfin\Inventory\Models\Product
     */
    public static function findBySku(string $sku)
    {
        return ProductSku::where('code', $sku)->firstOrFail();
    }

    /**
     * Scope for Find Product by sku
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string $sku
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereSku(Builder $query, string $sku): Builder
    {
        return $query->whereHas('skus', function ($q) use ($sku) {
            $q->where('code', $sku);
        });
    }

    /**
     * Create an sku for the product that has no
     * possible variation
     *
     * @param string $code
     * @throw \Rcdelfin\Inventory\Exceptions\InvalidVariantException
     * @return void
     */
    public function addSku(string $code, $price = 0.00, $cost = 0.00): void
    {
        if ($this->hasAttributes()) {
            throw new InvalidVariantException("Cannot add single SKU due to there's a possible variation", 400);
        }

        $this->skus()->create([
            'code' => $code,
            'price' => $price,
            'cost' => $cost
        ]);
    }

    /**
     * Product sku relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function skus(): HasMany
    {
        return $this->hasMany('Rcdelfin\Inventory\Models\ProductSku');
    }

    /**
     * Product Variations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany;
     */
    public function variations(): HasMany
    {
        return $this->hasMany('Rcdelfin\Inventory\Models\ProductVariant');
    }
}
