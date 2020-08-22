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
     *
     * @param string $customerId идентификатор уникально определяющий пользователя (соединение с пользователем)
     */
    public function waitForOrderCommand($customerId)
    {
        $sleep = 5;
        $counter = 0;

        $prev = Yii::$app->cache->get('order-command');
        if (!isset($prev[$customerId])) {
            $prev[$customerId] = [];
        }

        for (; ;) {
            $now = Yii::$app->cache->get('order-command');

            if (!isset($now[$customerId])) {
                $now[$customerId] = [];
            }

            if ($now[$customerId] != $prev[$customerId]) {
                foreach ($now[$customerId] as $orderUid => $orderInfo) {
                    if (empty($orderInfo['times_sent'])) {
                        $data = json_encode($orderInfo['info']);
                        echo "data: $data\n\n";
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
}
