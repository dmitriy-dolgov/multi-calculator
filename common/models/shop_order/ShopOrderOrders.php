<?php

namespace common\models\shop_order;

use common\models\db\CoWorker;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ShopOrderOrders extends ShopOrderWorker
{
    public function getActiveOrders($worker_uid)
    {
        $orders = [];

        if (!$coWorker = CoWorker::findOne(['worker_site_uid' => $worker_uid])) {
            throw new NotFoundHttpException();
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
            $orderObjs = ShopOrder::findAll($orderIds);
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
