<?php

namespace frontend\sse;

use odannyc\Yii2SSE\SSEBase;
use Sse\Data;

class MerchantOrderAccept extends SSEBase
{
    /** @var Data */
    protected $storage;

    /** @var string */
    protected $sessionId;

    protected $orderInfo;


    public function __construct($sessionId, $storage) {
        $this->sessionId = $sessionId;
        $this->storage = $storage;
    }

    public function update()
    {
        return $this->orderInfo['acceptedOrderData'];
    }

    public function check()
    {
        $this->orderInfo = json_decode($this->storage->get('order-info'));

        if ($this->orderInfo['sessionId'] == $this->sessionId) {
            return true;
        }

        return false;
    }
}
