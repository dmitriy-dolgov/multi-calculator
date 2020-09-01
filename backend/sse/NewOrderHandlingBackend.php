<?php

namespace backend\sse;

use Yii;

class NewOrderHandlingBackend extends OrderHandlingBackend
{
    const CO_WORKER_FUNCTION = 'accept_orders';

    protected $sseUsersByFunction = [];


    public function setSseUserIdForFunction()
    {
        $this->sseUsersByFunction[self::CO_WORKER_FUNCTION][] = $this->getSseUserId();
    }

    public function getSseUserListByFunction()
    {
        return $this->sseUsersByFunction[self::CO_WORKER_FUNCTION];
    }

    public static function addNewOrder($html)
    {
        $data = Yii::$app->cache->get(self::STORE_KEY);

        if (empty($data[self::$sseUserId])) {
            $data[self::$sseUserId] = [];
        }

        $data[self::$sseUserId][] = [
            'new-order' => $html,
        ];
    }
}
