<?php

namespace Rcdelfin\Inventory\Adapters;

use Rcdelfin\Inventory\Resources\AttributeResource;
use Rcdelfin\Inventory\Adapters\BaseAdapter;

class ProductAttributeAdapter extends BaseAdapter
{
    /**
     * Single resource transformer
     *
     * @param mixed $model
     */
    public function __construct($model)
    {
        parent::__construct(new AttributeResource($model));
    }

    /**
     * Static function for the collection
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    public static function collection($collection): array
    {
        $resource = new self($collection);
        $resource->setResource(AttributeResource::collection($collection));

        return $resource->transform();
    }
}
