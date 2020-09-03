<?php

namespace backend\sse;

use Yii;

class NewOrderHandlingBackend extends OrderHandlingBackend
{
    const CO_WORKER_FUNCTION = 'accept_orders';


    //protected $sseUsersByFunction = [];


    /** @var string название функции пользователя (accept_orders, courier ...) */
    /*protected $userFunction;


    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function setSseUserIdForFunction()
    {
        $this->sseUsersByFunction[self::CO_WORKER_FUNCTION][] = $this->getSseUserId();
    }

    public function getSseUserListByFunction()
    {
        return $this->sseUsersByFunction[self::CO_WORKER_FUNCTION];
    }*/

    public function getBaseStoreElement()
    {
        $updated = false;

        if (!$elem = Yii::$app->cache->get(self::STORE_KEY)) {
            $elem = [];
            $updated = true;
        }

        if (empty($elem[self::CO_WORKER_FUNCTION])) {
            $elem[self::CO_WORKER_FUNCTION] = [];
            $updated = true;
        }

        $sseUserId = $this->getSseUserId();
        if (empty($elem[self::CO_WORKER_FUNCTION][$sseUserId])) {
            $elem[self::CO_WORKER_FUNCTION][$sseUserId] = [];
            $updated = true;
        }

        if ($updated) {
            Yii::$app->cache->set(self::STORE_KEY, $elem);
        }

        return $elem[self::CO_WORKER_FUNCTION][$sseUserId];
    }

    public function handleIncomingSignals()
    {
        
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
