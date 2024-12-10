<?php

namespace Shopee\Nodes\FlashSale\Parameters;

use Shopee\Nodes\Item\Parameters\VariationIdTrait;
use Shopee\RequestParameters;

class ItemModel extends RequestParameters
{
    use VariationIdTrait;
    public function getInputPromoPrice(): float
    {
        return $this->parameters['input_promo_price'];
    }

    /**
     * Name of the discount.
     *
     * @param float $input_promo_price
     * @return $this
     */
    public function setInputPromoPrice(float $input_promo_price)
    {
        $this->parameters['input_promo_price'] = $input_promo_price;

        return $this;
    }

    public function getStock(): int
    {
        return $this->parameters['stock'];
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setStock(int $stock)
    {
        $this->parameters['stock'] = $stock;

        return $this;
    }

    public function getModelId(): int
    {
        return $this->parameters['model_id'];
    }

    /**
     * @param int $model_id
     * @return $this
     */
    public function setModelId(int $model_id)
    {
        $this->parameters['model_id'] = $model_id;

        return $this;
    }

}
