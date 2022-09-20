<?php

namespace Shopee\Nodes\Logistics;

use Illuminate\Support\Facades\Log;
use Shopee\ClientV2;
use Shopee\Nodes\NodeAbstractV2;
use Shopee\RequestParametersInterface;
use Shopee\ResponseData;

class Logistics extends NodeAbstractV2
{

    /**
     * Use this call to get all supported Logistic Channel.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function getLogistics($parameters = []): ResponseData
    {
        return $this->get("/api/v2/logistics/get_channel_list", ClientV2::API_TYPE_SHOP, $parameters);

    }
    /**
     * Get return list.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function getTrackingNumber($parameters = []): ResponseData
    {
        return $this->get('/api/v2/logistics/get_tracking_number', ClientV2::API_TYPE_SHOP, $parameters);
    }
}
