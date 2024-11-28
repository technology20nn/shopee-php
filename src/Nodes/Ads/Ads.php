<?php

namespace Shopee\Nodes\Ads;

use Shopee\ClientV2;
use Shopee\Nodes\NodeAbstractV2;
use Shopee\RequestParametersInterface;
use Shopee\ResponseData;

class Ads extends NodeAbstractV2
{

    /**
     * Use this call to get cqc ads daily performance
     * https://open.shopee.com/documents/v2/v2.ads.get_all_cpc_ads_daily_performance?module=117&type=1
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function getCPCAdsDailyPerformance($parameters = []): ResponseData
    {
        return $this->get("/api/v2/ads/get_all_cpc_ads_daily_performance", ClientV2::API_TYPE_SHOP, $parameters);
    }

    public function getCPCAdsHourlyPerformance($parameters = []): ResponseData
    {
        return $this->get("/api/v2/ads/get_all_cpc_ads_hourly_performance", ClientV2::API_TYPE_SHOP, $parameters);
    }

    public function getProductCampainDailyPerformance($parameters = []): ResponseData
    {
        return $this->get("/api/v2/ads/get_product_campaign_daily_performance", ClientV2::API_TYPE_SHOP, $parameters);
    }

}
