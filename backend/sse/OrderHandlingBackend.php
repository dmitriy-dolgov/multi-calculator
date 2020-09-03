<?php

namespace backend\sse;

use Yii;
use yii\base\BaseObject;

abstract class OrderHandlingBackend extends BaseObject
{
    protected static $sseUserId;


    //abstract public function setSseUserIdForFunction();

    //abstract public function getSseUserListByFunction();

    /**
     * Инициализирует и возвращает базовый элемент.
     *
     * @return mixed
     */
    abstract public static function getBaseUserElement();

    /**
     * Очистить ненужные данные при окончательном закрытии соединения.
     */
    abstract public function cleanOnConnectionClose();

    abstract public function handleIncomingSignals();


    public function waitForOrderCommand()
    {
        set_time_limit(0);    // наверное не надо благодаря text/event-stream ?

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        //header('Access-Control-Allow-Origin: *');
        //header('Connection: keep-alive');

        // см. connection_aborted() - https://kevinchoppin.dev/blog/server-sent-events-in-php
        ignore_user_abort(true);

        //$this->setSseUserIdForFunction();

        $sleep = 8;
        $counter = 0;

        //TODO: реализовать
        //$eventId = 1;

        $sseUserId = $this->getSseUserId();
        Yii::$app->session->close();

        // см. https://kevinchoppin.dev/blog/server-sent-events-in-php для использования полифила
        //echo ':' . str_repeat(' ', 2048) . "\n"; // 2 kB padding for IE
        //echo "retry: 2000\n";

        /*if (!$prev = Yii::$app->cacheSse->get(self::STORE_KEY)) {
            $prev = [];
        }

        if (empty($prev[$userFunction])) {
            $prev[$userFunction] = [];
        }

        if (empty($prev[$userFunction][$sseUserId])) {
            $prev[$userFunction][$sseUserId] = [];
        }*/

        //$prev = $this->getBaseUserElement();

        for (; ;) {
            if (connection_aborted()) {
                $this->cleanOnConnectionClose();
                Yii::debug('connection_aborted()', 'sse-order');
                exit();
            }

            /*if (!$now = Yii::$app->cacheSse->get(self::STORE_KEY)) {
                $now = [];
            }

            if (empty($now[$sseUserId])) {
                $now[$sseUserId] = [];
            }*/

            $now = $this->getBaseUserElement();

            //$r = $this->getSseUserListByFunction();
            Yii::debug('$sseUserId: ' . $sseUserId, 'sse-order');
            //Yii::debug('R: ' . print_r($r, true), 'sse-order');
            Yii::debug('$now: ' . print_r($now, true), 'sse-order');
            //Yii::debug('$prev: ' . print_r($prev, true), 'sse-order');

            $sc = Yii::$app->cacheSse->get(static::STORE_KEY);
            Yii::debug('Yii::$app->cacheSse->get(self::STORE_KEY): ' . print_r($sc, true), 'sse-order');

            echo "event: ping\n";
            echo 'data: ' . json_encode(['time' => time()]) . "\n\n";


            //if (in_array($sseUserId, $this->getSseUserListByFunction()) && $now[$sseUserId] != $prev[$sseUserId]) {
            //if ($now != $prev) {
            if (!empty($now)) {
                Yii::debug('$now != $prev', 'sse-order');

                $this->handleIncomingSignals();

                /*$nowDuplicateForUser = $now[$sseUserId];
                foreach ($nowDuplicateForUser as $eventName => $eventInfo) {
                    $data = json_encode($eventInfo);
                    echo "event: $eventName\n";
                    echo "data: $data\n\n";

                    unset($now[$sseUserId][$eventName]);
                }

                Yii::$app->cacheSse->set(self::STORE_KEY, $now);*/

                $counter = 0;
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

            //$prev = $now;

            sleep($sleep);
            $counter += $sleep;
        }
    }

    /*public function queryStart()
    {
        $sseUserId = $this->getSseUserId();
        $storeKey = $this->getStoreKey();

        $orderCommand = Yii::$app->cacheSse->get($storeKey);
        if (!isset($orderCommand[$sseUserId])) {
            $orderCommand[$sseUserId] = [];
        }

        Yii::$app->cacheSse->set($storeKey, $orderCommand);

        return $sseUserId;
    }*/

    public static function getSseUserId()
    {
        if (!self::$sseUserId) {
            self::$sseUserId = Yii::$app->session->getId();
        }

        return self::$sseUserId;
    }
}
