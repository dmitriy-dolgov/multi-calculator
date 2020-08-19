<?php

namespace common\models\shop_order;

use common\models\db\CoWorkerCoWorkerFunction;
use Yii;
use common\models\db\CoWorker;
use common\models\db\CoWorkerFunction;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ShopOrderAcceptorders extends ShopOrderWorker
{
    const FOR_ROLES = [
        'accept_orders',
    ];

    public function getActiveOrders($worker_uid)
    {
        $orders = [];

        if (!$coWorker = CoWorker::findOne(['worker_site_uid' => $worker_uid])) {
            Yii::error('Worker user id not found: "' . $worker_uid . '".');
            throw new NotFoundHttpException('Worker user id not found.');
        }

        //TODO: Проверка привилегий - улучшить
        if (!CoWorkerCoWorkerFunction::find()->andWhere(['co_worker_id' => $coWorker->id])->andWhere([
            'IN',
            'co_worker_function_id',
            self::FOR_ROLES
        ])->exists()) {
            Yii::error('Privilege not found for user with worker_uid: "' . $worker_uid . '", privilege is "' . self::FOR_ROLES . '".');
            throw new NotFoundHttpException('Privilege not found.');
        }

        if ($newShopOrders = ShopOrderStatus::find()
            ->select('shop_order_id')
            ->andWhere(['user_id' => $coWorker->user_id])
            ->andWhere(['type' => 'created'])
            //->groupBy('shop_order_id')
            ->orderBy(['shop_order_id' => SORT_DESC])
            ->asArray()
            ->all()
        ) {
            $orderIds = ArrayHelper::getColumn($newShopOrders, 'shop_order_id');
            $orderObjs = ShopOrder::find()->andWhere(['IN', 'id', $orderIds])->orderBy(['id' => SORT_DESC])->all();
            foreach ($orderObjs as $shopOrder) {
                $components = [];
                if ($shopOrder->shopOrderComponents) {
                    foreach ($shopOrder->shopOrderComponents as $soComponent) {
                        $components[] = [
                            // Данные непосредственно на момент подтверждения заказа
                            'on_deal' => ArrayHelper::toArray($soComponent),
                            // Данные на текущий момент
                            'on_current' => ArrayHelper::toArray($soComponent->component),
                        ];
                    }
                }
                $orders[] = [
                    'info' => ArrayHelper::toArray($shopOrder),
                    'components' => $components,
                ];
            }
        }

        return $orders;
    }
}
