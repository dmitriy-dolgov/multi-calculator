<?php

namespace backend\sse;

use Yii;

/**
 * <coWorkerFunction> => [      // Функция сотрудника (accept_orders, courier ...)
 *      <sseUserId>[            // Уникальный ID пользователя (ID сессии напр.)
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

    public static function getBaseUserElement()
    {
        //TODO: блокировать кеш

        $updated = false;

        if (!$elems = Yii::$app->cacheSse->get(self::STORE_KEY)) {
            $elems = [];
            $updated = true;
        }

        if (!isset($elems[self::CO_WORKER_FUNCTION])) {
            $elems[self::CO_WORKER_FUNCTION] = [];
            $updated = true;
        }

        $sseUserId = self::getSseUserId();
        if (!isset($elems[self::CO_WORKER_FUNCTION][$sseUserId])) {
            $elems[self::CO_WORKER_FUNCTION][$sseUserId] = [];
            $updated = true;
        }

        if ($updated) {
            Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
        }

        return $elems[self::CO_WORKER_FUNCTION][$sseUserId];
    }

    /*public static function setBaseStoreElement($bsElem)
    {
        if (!$elems = Yii::$app->cacheSse->get(self::STORE_KEY)) {
            $elems = [];
        }
        $elems[self::CO_WORKER_FUNCTION][self::getSseUserId()] = $bsElem;
        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }*/

    /**
     * @inheritDoc
     */
    public function handleIncomingSignals()
    {
        //TODO: блокировать кеш
        $elems = Yii::$app->cacheSse->get(self::STORE_KEY);

        $sseUserId = self::getSseUserId();

        foreach ($elems[self::CO_WORKER_FUNCTION][$sseUserId] as $ordinalId => $eventList) {
            if (!$eventName = array_key_first($eventList)) {
                Yii::error('No first element (event name) in function list!');
                continue;
            }
            $eventData = json_encode([
                'html' => $eventList[$eventName],
            ]);
            echo "event: $eventName\n";
            echo "data: $eventData\n\n";
            /*foreach ($currentStateElemCopy as $eventName => $eventData) {
                $data = json_encode($eventData);
                echo "event: $eventName\n";
                echo "data: $data\n\n";

                //TODO: возможно, отмечать признак отправки
                unset($elems[self::CO_WORKER_FUNCTION][$sseUserId][$eventName]);
            }*/
        }

        ob_flush();
        flush();

        $elems[self::CO_WORKER_FUNCTION][$sseUserId] = [];

        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }

    public static function addNewOrder($html)
    {
        //TODO: блокировать кеш

        if (!$elems = Yii::$app->cacheSse->get(self::STORE_KEY)) {
            $elems = [];
        }

        foreach ($elems as $coWorkerFunction => $userList) {
            if ($coWorkerFunction == self::CO_WORKER_FUNCTION) {
                foreach ($userList as $sseUserId => $userItem) {
                    $elems[$coWorkerFunction][$sseUserId][] = [
                        'new-order' => $html,
                    ];
                }
            }
        }

        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);

        /*$elems = self::getBaseUserElement();
        $elems[] = [
            'new-order' => $html,
        ];
        self::setBaseStoreElement($elems);*/

        /*$data = Yii::$app->cacheSse->get(self::STORE_KEY);

        $sseUserId = self::getSseUserId();

        if (empty($data[$sseUserId])) {
            $data[$sseUserId] = [];
        }

        $data[$sseUserId][] = [
            'new-order' => $html,
        ];

        return Yii::$app->cacheSse->set(self::STORE_KEY, $data);*/
    }

    public function cleanOnConnectionClose()
    {
        //TODO: блокировать кеш

        $elems = Yii::$app->cacheSse->get(self::STORE_KEY);
        unset($elems[self::CO_WORKER_FUNCTION][self::getSseUserId()]);
        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }
}
