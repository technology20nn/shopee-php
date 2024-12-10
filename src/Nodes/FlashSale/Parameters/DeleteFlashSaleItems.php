<?php

namespace Shopee\Nodes\FlashSale\Parameters;

use Shopee\Nodes\Item\Parameters\ItemTrait;
use Shopee\Nodes\Item\Parameters\VariationIdTrait;
use Shopee\RequestParameters;

class DeleteFlashSaleItems extends RequestParameters
{
    use FlashSaleTrait;
    use ItemTrait;
    use VariationIdTrait;

}
