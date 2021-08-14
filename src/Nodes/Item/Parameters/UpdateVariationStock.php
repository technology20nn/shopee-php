<?php

namespace Shopee\Nodes\Item\Parameters;

class UpdateVariationStock extends UpdateStock
{
    use VariationIdTrait;

    public function getVariationStock()
    {
        return $this->parameters['normal_stock'];
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setVariationStock(int $stock)
    {
        $this->parameters['normal_stock'] = $stock;

        return $this;
    }
}
