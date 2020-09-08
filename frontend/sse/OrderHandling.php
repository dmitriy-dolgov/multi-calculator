<?php

namespace frontend\sse;

use Yii;
use yii\base\BaseObject;

abstract class OrderHandling extends BaseObject
{
    protected static $sseUserId;


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

        ob_flush();
        flush();

        $sleep = 8;
        $counter = 0;

        //TODO: реализовать
        //$eventId = 1;

        Yii::$app->session->close();

        // см. https://kevinchoppin.dev/blog/server-sent-events-in-php для использования полифила
        //echo ':' . str_repeat(' ', 2048) . "\n"; // 2 kB padding for IE
        //echo "retry: 2000\n";

        for (; ;) {
            if (connection_aborted()) {
                $this->cleanOnConnectionClose();
                Yii::debug('connection_aborted()', 'sse-order');
                exit();
            }

            $now = static::getBaseUserElement();

            echo "event: ping\n";
            echo 'data: ' . json_encode(['time' => time() . '_data: ' . print_r($now, true)]) . "\n\n";
            ob_flush();
            flush();

            if (!empty($now)) {
                $this->handleIncomingSignals();
                $counter = 0;
            } else {
                // Send a little candy 15 seconds every in order not to disconnect
                if ($counter > 15) {
                    // То что начинается с двоеточия - комментарий SSE
                    echo ":[server] ping \n\n";
                    ob_flush();
                    flush();
                    $counter = 0;
                }
            }

            sleep($sleep);
            $counter += $sleep;
        }
    }

    public static function getSseUserId()
    {
        if (!self::$sseUserId) {
            self::$sseUserId = Yii::$app->session->getId();
        }

        return self::$sseUserId;
    }
}
