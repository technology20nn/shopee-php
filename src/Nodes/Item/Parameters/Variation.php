<?php

namespace Shopee\Nodes\Item\Parameters;

use Shopee\RequestParameters;

class Variation extends RequestParameters
{
    public function getTierIndex(): array
    {
        return $this->parameters['tier_index'];
    }

    /**
     *
     * @param array $index_list
     * @return $this
     */
    public function setTierIndex(array $index_list)
    {
        $this->parameters['tier_index'] = $index_list;

        return $this;
    }

    public function getStock(): int
    {
        return $this->parameters['normal_stock'];
    }

    /**
     * The current stock quantity of the variation in the listing currency.
     *
     * @param int $stock
     * @return $this
     */
    public function setStock(int $stock)
    {
        $this->parameters['normal_stock'] = $stock;

        return $this;
    }

    public function getPrice()
    {
        return $this->parameters['original_price'];
    }

    /**
     * The current price of the variation in the listing currency.
     *
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price)
    {
        $this->parameters['original_price'] = $price;

        return $this;
    }

    public function getVariationSku()
    {
        return $this->parameters['model_sku'];
    }

    /**
     * @param string $variationSku
     * @return $this
     */
    public function setVariationSku(string $variationSku)
    {
        $this->parameters['model_sku'] = $variationSku;

        return $this;
    }
}
