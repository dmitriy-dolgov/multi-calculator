<?php

namespace frontend\sse;

use Yii;
use odannyc\Yii2SSE\SSEBase;

class MerchantOrderAccept extends SSEBase
{
    /** @var string */
    protected $sessionId;

    protected $orderInfo;


    public function __construct($sessionId) {
        $this->sessionId = $sessionId;
    }

    public function update()
    {
        return json_encode($this->orderInfo['acceptedOrderData']);
    }

    public function check()
    {
        if ($this->orderInfo = json_decode(Yii::$app->cache->get('order-info'), true)) {
            if ($this->orderInfo['sessionId'] == $this->sessionId) {
                $sessionOrderIdsKey = ['order-accept', 'order-uids', 'session-id' => $this->sessionId];
                if ($data = Yii::$app->cache->get($sessionOrderIdsKey)) {
                    if (in_array($this->orderInfo['acceptedOrderData']['orderUid'], $data)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
