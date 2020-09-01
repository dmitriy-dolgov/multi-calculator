<?php

namespace backend\sse;

class NewOrderHandlingBackend extends OrderHandlingBackend
{
    const CO_WORKER_FUNCTION = 'accept_orders';

    protected $sseUsersByFunction = [];


    public function setSseUserIdForFunction()
    {
        $this->sseUsersByFunction[CO_WORKER_FUNCTION][] = $this->getSseUserId();
    }

    public function getSseUserListByFunction()
    {
        return $this->sseUsersByFunction[CO_WORKER_FUNCTION];
    }
}
