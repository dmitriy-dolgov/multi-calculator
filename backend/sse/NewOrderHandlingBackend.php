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

        $sseUserId = self::getSseUserId();

        if (empty($data[$sseUserId])) {
            $data[$sseUserId] = [];
        }

        $data[$sseUserId][] = [
            'new-order' => $html,
        ];

        return Yii::$app->cache->set(self::STORE_KEY, $data);
    }
}
