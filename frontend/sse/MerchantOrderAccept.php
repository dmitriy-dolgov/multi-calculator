<?php

namespace frontend\sse;

use odannyc\Yii2SSE\SSEBase;
use Sse\Data;

class MerchantOrderAccept extends SSEBase
{
    /** @var Data */
    protected $storage;

    public function __construct($data) {
        $this->storage = $data;
    }

    public function check()
    {
        return true;
    }

    public function update()
    {
        return "Something Cool";
    }
}
