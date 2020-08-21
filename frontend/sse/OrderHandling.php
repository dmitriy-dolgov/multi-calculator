<?php

namespace frontend\sse;

use Yii;
use yii\base\BaseObject;

class OrderHandling extends BaseObject
{
    /**
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

        /**
            "order-command"
                <customerId>
                    <orderId>


         */

        for (; ;) {
            $now = Yii::$app->cache->get('order-command');

            if ($now[$customerId] != $prev[$customerId]) {
                $now

                ob_flush();
                flush();
                $counter = 0;
            } else {
                // Send a little candy 15 seconds every in order not to disconnect
                if ($counter > 15) {
                    echo ":[server] How is everything ? ;) \n\n";
                    $counter = 0;
                }
            }

            $prev = $now;

            sleep($sleep);
            $counter += $sleep;
        }
    }


}
