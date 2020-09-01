<?php

namespace backend\sse;

use Yii;
use yii\base\BaseObject;

abstract class OrderHandlingBackend extends BaseObject
{
    /**
     * <sseUserId>[       // уникальный ID пользователя (ID сессии напр.)
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
        //set_time_limit(0);    // наверное не надо благодаря text/event-stream ?

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        //header('Access-Control-Allow-Origin: *');
        //header('Connection: keep-alive');

        // см. connection_aborted() - https://kevinchoppin.dev/blog/server-sent-events-in-php
        ignore_user_abort(true);

        $this->setSseUserIdForFunction();

        $sleep = 4;
        $counter = 0;

        //TODO: реализовать
        //$eventId = 1;

        $sseUserId = $this->getSseUserId();
        Yii::$app->session->close();

        $storeKey = $this->getStoreKey();

        // см. https://kevinchoppin.dev/blog/server-sent-events-in-php для использования полифила
        //echo ':' . str_repeat(' ', 2048) . "\n"; // 2 kB padding for IE
        //echo "retry: 2000\n";

        if (!$prev = Yii::$app->cache->get($storeKey)) {
            $prev = [];
        }

        if (!isset($prev[$sseUserId])) {
            $prev[$sseUserId] = [];
        }

        for (; ;) {
            if (connection_aborted()) {
                exit();
            }

            if (!$now = Yii::$app->cache->get($storeKey)) {
                $now = [];
            }

            if (!isset($now[$sseUserId])) {
                $now[$sseUserId] = [];
            }

            if ($now[$sseUserId] != $prev[$sseUserId]) {
                if (in_array($sseUserId, $this->getSseUserListByFunction())) {
                    $nowDuplicateForUser = $now[$sseUserId];
                    foreach ($nowDuplicateForUser as $eventName => $eventInfo) {
                        $data = json_encode($eventInfo);
                        echo "event: $eventName\n";
                        echo "data: $data\n\n";

                        unset($now[$sseUserId][$eventName]);
                    }

                    Yii::$app->cache->set($storeKey, $now);

                    ob_flush();
                    flush();
                    $counter = 0;
                }
            } else {
                // Send a little candy 15 seconds every in order not to disconnect
                if ($counter > 15) {
                    //echo 'event: ping';   // То что начинается с двоеточия - комментарий SSE
                    echo ":[server] ping \n\n";
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

    abstract public function setSseUserIdForFunction();

    abstract public function getSseUserListByFunction();

    /*public function queryStart()
    {
        $sseUserId = $this->getSseUserId();
        $storeKey = $this->getStoreKey();

        $orderCommand = Yii::$app->cache->get($storeKey);
        if (!isset($orderCommand[$sseUserId])) {
            $orderCommand[$sseUserId] = [];
        }

        Yii::$app->cache->set($storeKey, $orderCommand);

        return $sseUserId;
    }*/

    public function getSseUserId()
    {
        return Yii::$app->session->getId();
    }

    public function getStoreKey()
    {
        return ':sse-backend-command';
    }
}
