<?php

namespace frontend\sse;

use Yii;

/**
 * TODO: stoppen on
 * <sseUserId>[                         // Уникальный ID пользователя (ID сессии напр.).
 *                                      // Такие элементы, с ключем ID пользователя, представляют покупателей пиццы.
 *      <orderUid>[                     // UID заказа
 *          [
 *                 <eventName> =>               // Название события (ping, accepted-by-merchant, ...)
 *                      <any event data>        // Данные события
 *          ],
 *          ...
 *      ],
 *      ...
 * ],
 * ...
 */
class CustomerWaitResponseOrderHandling extends OrderHandling
{
    const STORE_KEY = ':sse-frontend-CustomerWaitResponseOrderHandling';


    public static function getBaseUserElement()
    {
        //TODO: блокировать кеш

        $updated = false;

        if (!$elems = Yii::$app->cacheSse->get(self::STORE_KEY)) {
            $elems = [];
            $updated = true;
        }

        $sseUserId = self::getSseUserId();
        if (!isset($elems[$sseUserId])) {
            $elems[$sseUserId] = [];
            $updated = true;
        }

        if ($updated) {
            Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
        }

        return $elems[$sseUserId];
    }

    /**
     * @inheritDoc
     */
    public function handleIncomingSignals()
    {
        //TODO: блокировать кеш
        $elems = Yii::$app->cacheSse->get(self::STORE_KEY);

        $sseUserId = self::getSseUserId();

        foreach ($elems[$sseUserId] as $orderUid => $orderList) {
            foreach ($orderList as $ordinalId => $eventList) {
                if (!$eventName = array_key_first($eventList)) {
                    Yii::error('No first element (event name) in function list!', 'sse-order');
                    continue;
                }
                Yii::info('Event for SSE: `' . $eventName . '`', 'sse-order');
                $eventData = json_encode([
                    'orderUid' => $orderUid,
                    'data' => $eventList[$eventName],
                ]);
                echo "event: $eventName\n";
                echo "data: $eventData\n\n";
            }
        }

        ob_flush();
        flush();

        // Очистка всех событий
        $elems[$sseUserId] = [];

        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }

    public static function acceptOrderByMerchant($acceptedOrderUid, $merchantData)
    {
        //TODO: блокировать кеш

        if (!$elems = Yii::$app->cacheSse->get(self::STORE_KEY)) {
            $elems = [];
        }

        foreach ($elems as $sseUserId => $orderList) {
            foreach ($orderList as $orderUid => $orderEventList) {
                if ($orderUid == $acceptedOrderUid) {
                    $elems[$sseUserId][$orderUid][] = [
                        'accepted-by-merchant' => $merchantData,
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
        unset($elems[self::getSseUserId()]);
        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }
}
