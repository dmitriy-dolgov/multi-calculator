<?php

namespace common\modules\db;

use yii\base\BootstrapInterface;


class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->getDb()->createCommand('SET GLOBAL NET_READ_TIMEOUT=3600')->execute();
    }
}
