<?php

namespace backend\sse;

use Yii;

/**
 * <userFunction> => [     // Функция пользователя (accept_orders, courier ...)
 *      <sseUserId>[       // Уникальный ID пользователя (ID сессии напр.)
 *          [
 *              <eventName> =>              // Название события (ping, new-order ...)
 *                  <any event data>        // Данные события
 *          ],
 *          ...
 *      ],
 *      ...
 * ],
 * ...
 */
class NewOrderHandlingBackend extends OrderHandlingBackend
{
    const STORE_KEY = ':sse-backend-command_NewOrderHandlingBackend';

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
        $this->sseUsersByFunction[self::CO_WORKER_FUNCTION][] = self::getSseUserId();
    }

    public function getSseUserListByFunction()
    {
        return $this->sseUsersByFunction[self::CO_WORKER_FUNCTION];
    }*/

    public static function getBaseStoreElement()
    {
        //TODO: блокировать кеш

        $updated = false;

        if (!$elem = Yii::$app->cache->flush()->get(self::STORE_KEY)) {
            $elem = [];
            $updated = true;
        }

        if (!isset($elem[self::CO_WORKER_FUNCTION])) {
            $elem[self::CO_WORKER_FUNCTION] = [];
            $updated = true;
        }

        $sseUserId = self::getSseUserId();
        if (!isset($elem[self::CO_WORKER_FUNCTION][$sseUserId])) {
            $elem[self::CO_WORKER_FUNCTION][$sseUserId] = [];
            $updated = true;
        }

        if ($updated) {
            Yii::$app->cache->set(self::STORE_KEY, $elem);
        }

        return $elem[self::CO_WORKER_FUNCTION][$sseUserId];
    }

    public function setBaseStoreElement($bsElem)
    {
        if (!$elem = Yii::$app->cache->get(self::STORE_KEY)) {
            $elem = [];
        }
        $elem[self::CO_WORKER_FUNCTION][self::getSseUserId()] = $bsElem;
        Yii::$app->cache->set(self::STORE_KEY, $elem);
    }

    /**
     * @inheritDoc
     */
    public function handleIncomingSignals()
    {
        //TODO: блокировать кеш
        $elem = Yii::$app->cache->get(self::STORE_KEY);

        $sseUserId = self::getSseUserId();

        foreach ($elem[self::CO_WORKER_FUNCTION][$sseUserId] as $ordinalId => $eventList) {
            if (!$eventName = array_key_first($eventList)) {
                Yii::error('No first element (event name) in function list!');
                continue;
            }
            $eventData = json_encode($eventList[$eventName]);
            echo "event: $eventName\n";
            echo "data: $eventData\n\n";
            /*foreach ($currentStateElemCopy as $eventName => $eventData) {
                $data = json_encode($eventData);
                echo "event: $eventName\n";
                echo "data: $data\n\n";

                //TODO: возможно, отмечать признак отправки
                unset($elem[self::CO_WORKER_FUNCTION][$sseUserId][$eventName]);
            }*/
        }

        ob_flush();
        flush();

        $elem[self::CO_WORKER_FUNCTION][$sseUserId] = [];

        Yii::$app->cache->set(self::STORE_KEY, $elem);
    }

    public static function addNewOrder($html)
    {
        //TODO: блокировать кеш

        $elem = self::getBaseStoreElement();
        $elem[] = [
            'new-order' => $html,
        ];
        self::setBaseStoreElement($elem);
        
        /*$data = Yii::$app->cache->get(self::STORE_KEY);

        $sseUserId = self::getSseUserId();

        if (empty($data[$sseUserId])) {
            $data[$sseUserId] = [];
        }

        $data[$sseUserId][] = [
            'new-order' => $html,
        ];

        return Yii::$app->cache->set(self::STORE_KEY, $data);*/
    }

    public static function cleanOnConnectionClose()
    {
        //TODO: блокировать кеш

        $elem = Yii::$app->cache->get(self::STORE_KEY);
        unset($elem[self::CO_WORKER_FUNCTION][self::getSseUserId()]);
        Yii::$app->cache->set(self::STORE_KEY, $elem);
    }
}
