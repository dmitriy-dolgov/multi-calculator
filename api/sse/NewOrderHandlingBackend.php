<?php

namespace api\sse;

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
    const STORE_KEY = ':sse-api-command_NewOrderHandlingBackend';

    const CO_WORKER_FUNCTION = 'accept_orders';


    public static function getBaseUserElement()
    {
        //TODO: блокировать кеш

        $updated = false;

        if (($elems = Yii::$app->cacheSse->get(self::STORE_KEY)) === false) {
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
                Yii::error('No first element (event name) in function list!', 'sse-order');
                continue;
            }
            Yii::info('Event for SSE: `' . $eventName . '`', 'sse-order');
            $eventData = json_encode([
                'html' => $eventList[$eventName],
            ]);
            echo "event: $eventName\n";
            echo "data: $eventData\n\n";
        }

        ob_flush();
        flush();

        $elems[self::CO_WORKER_FUNCTION][$sseUserId] = [];

        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }

    public static function addNewOrder($html)
    {
        //TODO: блокировать кеш

        if (($elems = Yii::$app->cacheSse->get(self::STORE_KEY)) === false) {
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
    }

    public function cleanOnConnectionClose()
    {
        //TODO: блокировать кеш

        $elems = Yii::$app->cacheSse->get(self::STORE_KEY);
        unset($elems[self::CO_WORKER_FUNCTION][self::getSseUserId()]);
        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }
}
