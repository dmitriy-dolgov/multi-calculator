<?php

namespace frontend\sse;

use Yii;
use yii\base\BaseObject;

class OrderHandling extends BaseObject
{
    /**
     *  "order-command"[
     *      <customerId>[               // уникальный ID пользователя (ID сессии напр.)
     *          <orderUid>[             // UID заказа
     *              - info              // данные для отправки пользователю
     *              - times_sent        // сколько было ответов пользователю (отправки параметра `data`)
     *          ],
     *          ...
     *      ],
     *      ...
     *  ]
     */
    public function waitForOrderCommand()
    {
        $sleep = 4;
        $counter = 0;

        $customerId = $this->getUserUniqueId();
        Yii::$app->session->close();

        $storeKey = $this->getStoreKey();

        //$prev = Yii::$app->cache->get($storeKey);
        $prev = [];
        if (!isset($prev[$customerId])) {
            $prev[$customerId] = [];
        }

        for (; ;) {
            //$now = Yii::$app->cache->get($storeKey);
            $now = $prev;

            if (!isset($now[$customerId])) {
                $now[$customerId] = [];
            }

            if ($now[$customerId] != $prev[$customerId]) {
                foreach ($now[$customerId] as $orderUid => $orderInfo) {
                    if (empty($orderInfo['times_sent'])) {
                        $data = json_encode($orderInfo['info']);
                        echo "data: aname $data \n\n";
                        $orderInfo['times_sent'] = 1;
                    }
                }

                ob_flush();
                flush();
                $counter = 0;
            } else {
                // Send a little candy 15 seconds every in order not to disconnect
                if ($counter > 15) {
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
        return get_called_class() . ':order-command';
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
