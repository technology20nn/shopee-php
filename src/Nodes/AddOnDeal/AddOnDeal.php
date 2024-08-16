<?php

namespace Shopee\Nodes\Discount;

use Shopee\ClientV2;
use Shopee\Nodes\NodeAbstractV2;
use Shopee\RequestParametersInterface;
use Shopee\ResponseData;

class AddOnDeal extends NodeAbstractV2
{
    /**
     * Use this api to add shop discount activity.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function addAddOnDeal($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/add_add_on_deal', ClientV2::API_TYPE_SHOP, $parameters);
    }

    /**
     * Use this api to add shop discount item.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function addAddOnDealMainItem($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/add_add_on_deal_main_item', ClientV2::API_TYPE_SHOP, $parameters);
    }

    /**
     * Use this api to delete one discount activity BEFORE it starts.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function addAddOnDealSubItem($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/add_add_on_deal_sub_item', ClientV2::API_TYPE_SHOP, $parameters);
    }

    /**
     * Use this api to delete items of the discount activity.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function deleteAddOnDeal($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/delete_add_on_deal', ClientV2::API_TYPE_SHOP, $parameters);
    }

    /**
     * Use this api to get one shop discount activity detail.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function deleteAddOnDealMainItem($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/delete_add_on_deal_main_item', ClientV2::API_TYPE_SHOP, $parameters);
    }

    /**
     * Use this api to get shop discount activity list.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function deleteAddOnDealSubItem($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/delete_add_on_deal_sub_item', ClientV2::API_TYPE_SHOP, $parameters);
    }

    /**
     * Use this api to update one discount information
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function updateAddOnDeal($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/update_add_on_deal', ClientV2::API_TYPE_SHOP, $parameters);
    }

    /**
     * Use this api to update items of the discount activity.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function updateAddOnDealMainItem($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/update_add_on_deal_main_item', ClientV2::API_TYPE_SHOP, $parameters);
    }
    public function updateAddOnDealSubItem($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/update_add_on_deal_sub_item', ClientV2::API_TYPE_SHOP, $parameters);
    }

    public function endAddOnDeal($parameters = []): ResponseData
    {
        return $this->post('/api/v2/add_on_deal/end_add_on_deal', ClientV2::API_TYPE_SHOP, $parameters);
    }

    public function getAddOnDealList($parameters = []): ResponseData
    {
        return $this->get('/api/v2/add_on_deal/get_add_on_deal_list', ClientV2::API_TYPE_SHOP, $parameters);
    }

    public function getAddOnDeal($parameters = []): ResponseData
    {
        return $this->get('/api/v2/add_on_deal/get_add_on_deal', ClientV2::API_TYPE_SHOP, $parameters);
    }
    public function getAddOnDealMainItem($parameters = []): ResponseData
    {
        return $this->get('/api/v2/add_on_deal/get_add_on_deal_main_item', ClientV2::API_TYPE_SHOP, $parameters);
    }
    public function getAddOnDealSubItem($parameters = []): ResponseData
    {
        return $this->get('/api/v2/add_on_deal/get_add_on_deal_sub_item', ClientV2::API_TYPE_SHOP, $parameters);
    }
}
