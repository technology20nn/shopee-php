<?php

namespace Shopee\Nodes\Item\Parameters;

class UpdateVariationStock extends UpdateStock
{
    use VariationIdTrait;

    public function getVariationStock()
    {
        return $this->parameters['seller_stock'];
    }

    /**
     * @param SellerStock $stock
     * @return $this
     */
    public function setVariationStock(SellerStock $stock)
    {
        $this->parameters['seller_stock'] = $stock;

        return $this;
    }
}
