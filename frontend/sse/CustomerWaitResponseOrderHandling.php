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

        if (($elems = Yii::$app->cacheSse->get(self::STORE_KEY)) === false) {
            $elems = [];
            $updated = true;
        }

        $sseUserId = self::getSseUserId();
        if (!isset($elems[$sseUserId])) {
            $elems[$sseUserId] = [];
            $updated = true;
        }

        if ($updated) {
            //Yii::debug('getBaseUserElement(). $sseUserId: `' . $sseUserId . '`', 'sse-order');
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

        $updated = false;

//        echo "event: ping\n";
//        echo 'data: ' . 'IN HIS: ' . json_encode(['time' => $sseUserId . '__data: ' . print_r($elems, true)]) . "\n\n";
//        ob_flush();
//        flush();

        $elemsSseUserIdCopy = $elems[$sseUserId];
        foreach ($elemsSseUserIdCopy as $orderUid => $orderList) {
            if ($orderList) {
                foreach ($orderList as $ordinalId => $eventList) {
                    if (!$eventName = array_key_first($eventList)) {
                        Yii::error('No first element (event name) in function list!', 'sse-order');
                        continue;
                    }
                    //Yii::debug('Event for SSE: `' . $eventName . '`', 'sse-order');
                    $eventData = json_encode([
                        'orderUid' => $orderUid,
                        'data' => $eventList[$eventName],
                    ]);
                    echo "event: $eventName\n";
                    echo "data: $eventData\n\n";
                }

                $elems[$sseUserId][$orderUid] = [];
                $updated = true;
            }
        }

        ob_flush();
        flush();

//        echo "event: ping\n";
//        echo 'data: ' . 'IN HIS AFTER: ' . json_encode(['time' => $sseUserId . '__data: ' . print_r($elems, true)]) . "\n\n";
//        ob_flush();
//        flush();

        if ($updated) {
            //Yii::debug('handleIncomingSignals(). $sseUserId: `' . $sseUserId . '`; ELS: ' . print_r($elems, true), 'sse-order');
            Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
        }
    }

    /**
     * Вызвать у заказчика событие заказа.
     *
     * @param string $eventOrderUid UID обрабатываемого заказа
     * @param string $eventName название события, вызываемого у пользователя (напр. 'accepted-by-merchant')
     * @param mixed $eventData данные, передаваемыые событию $eventName
     * @return bool
     */
    public static function sendOrderStateChangeToCustomer(string $eventOrderUid, string $eventName, $eventData)
    {
        //TODO: блокировать кеш

        $accepted = false;

        if (($elems = Yii::$app->cacheSse->get(self::STORE_KEY)) === false) {
            $elems = [];
        }

        foreach ($elems as $sseUserId => $orderList) {
            foreach ($orderList as $orderUid => $orderEventList) {
                if ($orderUid == $eventOrderUid) {
                    $elems[$sseUserId][$orderUid][] = [
                        $eventName => $eventData,
                    ];
                    $accepted = true;
                }
            }
        }

        if ($accepted) {
            //Yii::debug('sendOrderStateChangeToCustomer(); ELS: ' . print_r($elems, true), 'sse-order');
            Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
        }

        return $accepted;
    }

    /**
     * Запуск заказчиком пиццы прослушки статуса заказа.
     *
     * @param $orderUid UID заказа
     */
    public static function startListenOrderStatus($orderUid)
    {
        //TODO: блокировать кеш

        if (($elems = Yii::$app->cacheSse->get(self::STORE_KEY)) === false) {
            $elems = [];
        }

        $sseUserId = self::getSseUserId();
        if (!isset($elems[$sseUserId][$orderUid])) {
            $elems[$sseUserId][$orderUid] = [];
        } else {
            Yii::debug('Order already exists: `' . $orderUid . '`', 'sse-order');
        }

        //Yii::debug('startListenOrderStatus(); ELS: ' . print_r($elems, true), 'sse-order');
        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }

    /**
     * Соединение обрывается внезапно, подозреваю потому что запрос выполняется дольше 3 секунд (retry для браузера по умолчанию)
     * TODO: очищать очередь как-нибудь по-другому
     * UPD: переделал функцию для очистки вручную (нпр
     */
    public static function cleanUserData()
    {
        //TODO: блокировать кеш

        $elems = Yii::$app->cacheSse->get(self::STORE_KEY);
        unset($elems[self::getSseUserId()]);
        Yii::debug('cleanUserData(). $sseUserId: `' . self::getSseUserId() . '`; ELS: ' . print_r($elems, true), 'sse-order');
        Yii::$app->cacheSse->set(self::STORE_KEY, $elems);
    }
}
