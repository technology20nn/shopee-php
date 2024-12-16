<?php

namespace Shopee\Nodes\FlashSale\Parameters;

use Shopee\RequestParameterCollection;
use Shopee\RequestParameters;
use Shopee\RequestParametersInterface;

class FlashSaleItemList extends RequestParameterCollection
{
    /**
     * @param ItemModel|RequestParametersInterface $parameter
     * @return $this
     */
    public function add(RequestParametersInterface $parameter)
    {
        parent::add($parameter);

        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function fromArray(array $parameters)
    {
        foreach ($parameters as $parameter) {
            $this->add(new FlashSaleItem($parameter));
        }

        return $this;
    }
}