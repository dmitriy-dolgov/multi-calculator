<?php

namespace common\models\shop_order;

use Yii;
use common\models\db\CoWorker;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use yii\base\Component;

abstract class ShopOrderWorker extends Component
{
    /** @var string */
    protected $workerUid;

    /** @var CoWorker|null */
    protected $workerObj;


    abstract public function getActiveOrders();


    public function __construct(string $workerUid, $config = [])
    {
        if (!$this->workerObj = CoWorker::findOne(['worker_site_uid' => $workerUid])) {
            Yii::error('Co-worker not found: `' . $workerUid . '``');
            throw new InternalErrorException('Co-worker not found.');
        }

        $this->workerUid = $workerUid;

        parent::__construct($config);
    }

    public function getWorkerObj()
    {
        return $this->workerObj;
    }

    public function setWorkerObj($workerObj)
    {
        $this->workerObj = $workerObj;
    }
}
