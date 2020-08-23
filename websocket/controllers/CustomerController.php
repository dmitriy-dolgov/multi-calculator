<?php

namespace websocket\controllers;

use immusen\websocket\src\Controller;

/**
 * Class CustomerController
 * @package websocket\controllers
 *
 * Группы пользователей:
 *      1 - покупатели пиццы
 *      2 - сотрудники принимающие заказы
 */
class CustomerController extends Controller
{

    const CUSTOMER_MEMBER_COUNT_KEY = 'ws_record_hash_#customer';


    public function beforeAction()
    {
        parent::beforeAction();
    }

    /**
     * Client who join an customer
     * @param $id , customer id
     * @param null $info , some extra data
     * @return bool
     */
    public function actionJoin($id, $info = null)
    {
        #AUTH
        //$client_info = $this->server->connection_info($this->fd);
        //User auth and client bind @see \websocket\controllers\UserController::actionAuth
        //if (empty($client_info['uid']))
        //    return $this->publish($this->fd, ['type' => 'error', 'msg' => 'Permission denied']);

        //add fd into group with customer id as the key
        $this->joinGroup($this->fd, $id);

        $member_count = $this->redis->hincrby(self::CUSTOMER_MEMBER_COUNT_KEY, $id, 1);

        //Get all fds in this customer/group;
        $targets = $this->groupMembers($id);
        //Broadcast to every client
        return $this->sendToGroup(['type' => 'join', 'count' => $member_count, 'info' => $info], $id);

        //Or send history message to this client (fake code)
        //$this->publish($this->fd, $this->getHistoryMessageFunction());
    }

    /**
     * some one leave customer similar with actionJoin
     * @param $id , customer id
     * @param $info
     * @return bool
     */
    public function actionLeave($id, $info = null)
    {
        $member_count = $this->redis->hincrby(self::CUSTOMER_MEMBER_COUNT_KEY, $id, -1);
        $this->sendToGroup(['type' => 'leave', 'count' => $member_count, 'info' => $info], $id);
        //del fd from group
        return $this->leaveGroup($this->fd, $id);
    }

    /**
     * Message to customer, e.g. orderTeam/msg
     * ```JSON
     * {
     *       "jsonrpc":"2.0",
     *       "id":1,
     *       "method":"orderTeam/msg",
     *       "params":{
     *           "id":"100111",
     *           "content":{
     *               "text":"Hello world!"
     *           }
     *       }
     *   }
     * ```
     * @param $id
     * @param $content
     * @return bool
     */
    public function actionMsg($id, $content = null)
    {
        return $this->sendToGroup($content, $id);
        //same as $this->publish($this->groupMembers($id), $content);
    }

    public function actionNewOrderCreated($id, $info)
    {
        echo "actionNewOrderCreated()\n";
        echo "ID: $id\n";
        print_r($info);

        if ($id == 1) {
            $content = [];

            return $this->sendToGroup($content, 2);
        }

    }

}