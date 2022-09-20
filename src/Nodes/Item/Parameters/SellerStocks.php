<?php

namespace Shopee\Nodes\Item\Parameters;

use Shopee\RequestParameterCollection;
use Shopee\RequestParametersInterface;

class SellerStocks extends RequestParameterCollection
{
    /**
     * @param Logistic|RequestParametersInterface $parameter
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
     * @throws \ReflectionException
     */
    public function fromArray(array $parameters)
    {
        foreach ($parameters as $parameter) {
            $this->add(new SellerStock($parameter));
        }

        return $this;
    }
}
