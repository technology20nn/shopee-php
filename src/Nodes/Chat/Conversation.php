<?php

namespace Shopee\Nodes\Chat;

use Shopee\ClientV2;
use Shopee\Nodes\NodeAbstractV2;
use Shopee\RequestParametersInterface;
use Shopee\ResponseData;

class Conversation extends NodeAbstractV2 {
	/**
	 * Use this call to get information of shop.
	 * https://open.shopee.com/documents/v2/v2.sellerchat.get_conversation_list?module=109&type=1
	 * @param array|RequestParametersInterface $parameters
	 * @return ResponseData
	 */
	public function getConversationList($parameters = []): ResponseData {
		return $this->get("/api/v2/sellerchat/get_conversation_list", ClientV2::API_TYPE_SHOP, $parameters);
	}
	/**
	 * Use this call to get information of shop.
	 * https://open.shopee.com/documents/v2/v2.sellerchat.get_one_conversation?module=109&type=1
	 * @param array|RequestParametersInterface $parameters
	 * @return ResponseData
	 */
	public function getOneConversation($parameters = []): ResponseData {
		return $this->get("/api/v2/sellerchat/get_one_conversation", ClientV2::API_TYPE_SHOP, $parameters);
	}

    /**
     * Use this call to get information of shop.
     * https://open.shopee.com/documents/v2/v2.sellerchat.read_conversation?module=109&type=1
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function readConversation($parameters = []): ResponseData {
        return $this->post("/api/v2/sellerchat/read_conversation", ClientV2::API_TYPE_SHOP, $parameters);
    }
}
