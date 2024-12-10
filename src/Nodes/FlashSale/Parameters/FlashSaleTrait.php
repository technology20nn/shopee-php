<?php


namespace Shopee\Nodes\FlashSale\Parameters;

trait FlashSaleTrait
{
    public function getFlashSaleId(): int
    {
        return $this->parameters['flase_sale_id'];
    }

    /**
     * @param int $flase_sale_id
     * @return $this
     */
    public function setFlashSaleId(int $flase_sale_id)
    {
        $this->parameters['flase_sale_id'] = $flase_sale_id;

        return $this;
    }
}
