<?php

namespace backend\sse;

use Yii;
use yii\base\BaseObject;

class OrderHandlingBackend extends BaseObject
{
    /**
     * <customerId>[       // уникальный ID пользователя (ID сессии напр.)
     *          [
     *              <eventName> =>              // Название события (ping, new-order ...)
     *                  <any event data>        // Данные события
     *          ],
     *          ...
     * ],
     * ...
     */
    public function waitForOrderCommand()
    {
        set_time_limit(0);

        $sleep = 4;
        $counter = 0;

        $customerId = $this->getUserUniqueId();
        Yii::$app->session->close();

        $storeKey = $this->getStoreKey();

        $prev = [];
        if (!isset($prev[$customerId])) {
            $prev[$customerId] = [];
        }

        $eventId = 1;

        for (; ;) {
            $now = $prev;

            if (!isset($now[$customerId])) {
                $now[$customerId] = [];
            }

            if ($now[$customerId] != $prev[$customerId]) {
                foreach ($now[$customerId] as $eventName => $eventInfo) {
                    $data = json_encode($eventInfo);
                    echo "event: $eventName\n";
                    echo "data: $data\n\n";
                    $orderInfo['times_sent'] = 1;
                }

                ob_flush();
                flush();
                $counter = 0;
            } else {
                // Send a little candy 15 seconds every in order not to disconnect
                if ($counter > 15) {
                    //echo 'event: ping';   // То что начинается с двоеточия - комментарий SSE
                    echo ":[server] How is everything ? ;) \n\n";
                    ob_flush();
                    flush();
                    $counter = 0;
                }
            }

            $prev = $now;

            sleep($sleep);
            $counter += $sleep;
        }
    }

    public function queryStart()
    {
        $userUid = $this->getUserUniqueId();
        $storeKey = $this->getStoreKey();

        $orderCommand = Yii::$app->cache->get($storeKey);
        if (!isset($orderCommand[$userUid])) {
            $orderCommand[$userUid] = [];
        }

        Yii::$app->cache->set($storeKey, $orderCommand);

        return $userUid;
    }

    public function getUserUniqueId()
    {
        return Yii::$app->session->getId();
    }

    public function getStoreKey()
    {
        return ':sse-backend-command';
    }

    public function startOrderAccept($orderUid)
    {
        $this->queryStart();

        $userUid = $this->getUserUniqueId();
        $storeKey = $this->getStoreKey();

        $orderCommand = Yii::$app->cache->get($storeKey);

        if (!isset($orderCommand[$userUid][$orderUid])) {
            $orderCommand[$userUid][$orderUid] = [];
        }
        if (!isset($orderCommand[$userUid][$orderUid]['info'])) {
            $orderCommand[$userUid][$orderUid]['info'] = [];
        }

        return Yii::$app->cache->set($storeKey, $orderCommand);
    }
}
