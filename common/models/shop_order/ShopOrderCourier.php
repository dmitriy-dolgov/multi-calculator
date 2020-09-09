<?php

namespace common\models\shop_order;

use common\models\db\CoWorkerCoWorkerFunction;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use common\models\db\ShopOrderUser;
use common\models\db\User;
use frontend\sse\CustomerWaitResponseOrderHandling;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\web\NotFoundHttpException;

class ShopOrderCourier extends ShopOrderWorker
{
    const FOR_ROLES = [
        'courier',
    ];


    public function getActiveOrders()
    {
        $orders = [];

        //TODO: Проверка привилегий - улучшить
        if (!CoWorkerCoWorkerFunction::find()->andWhere(['co_worker_id' => $this->workerObj->id])->andWhere([
            'IN',
            'co_worker_function_id',
            self::FOR_ROLES,
        ])->exists()) {
            Yii::error('Privilege not found for user with worker_uid: "' . $this->workerUid . '", privilege is "' . self::FOR_ROLES . '".');
            throw new NotFoundHttpException('Privilege not found.');
        }

        if ($newShopOrders = ShopOrderStatus::find()
            ->andWhere(['user_id' => $this->workerObj->user_id])
            ->andWhere(['!=', 'type', 'finished'])
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all()
        ) {
            // Получаем последний статус заказа
            $orderTypes = [];
            foreach ($newShopOrders as $ord) {
                $orderTypes[$ord['shop_order_id']] = $ord['type'];
            }
            $orderIds = [];
            foreach ($orderTypes as $shopOrderId => $ordType) {
                if ($ordType == 'accepted-by-courier') {
                    $orderIds[] = $shopOrderId;
                }
            }

            $orderObjs = ShopOrder::find()->andWhere(['IN', 'id', $orderIds])->orderBy(['id' => SORT_DESC])->all();
            foreach ($orderObjs as $shopOrder) {
                $orderData = self::getAnOrder($shopOrder);
                $orderData['status'] = $orderTypes[$shopOrder->id];
                $orders[] = $orderData;
            }
        }

        return $orders;
    }

    public function acceptOrder($orderId)
    {
        $result = ['status' => 'error'];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                //$currentTimestamp = date('Y-m-d H:i:s');

                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                $shopOrderStatus->type = 'accepted-by-courier';
                $shopOrderStatus->user_id = $this->workerObj->user_id;
                $shopOrderStatus->accepted_at = date('Y-m-d H:i:s');
                $shopOrderStatus->accepted_by = $this->workerObj->id;
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);

                if (!$merchant = User::findOne($this->workerObj->user_id)) {
                    Yii::error('User not found. User id: ' . $this->workerObj->user_id);
                    throw new InternalErrorException('User not found!');
                }

                $acceptedOrderData = [
                    //'order_status' => 'accepted-by-courier',
                    'orderUid' => $shopOrder->order_uid,
                    'merchantData' => [
                        'name' => $merchant->profile->name,
                        'address' => $merchant->profile->location,
                        'company_lat_long' => $merchant->profile->company_lat_long,
                    ],
                    'courierData' => [
                        'name' => $this->workerObj->name,
                    ],
                ];

                if (CustomerWaitResponseOrderHandling::sendOrderStateChangeToCustomer(
                    $shopOrder->order_uid,
                    'accepted-by-courier',
                    $acceptedOrderData
                )) {
                    $result['status'] = 'success';
                } else {
                    $result['status'] = 'warning-custom';
                    $result['msg'] = Yii::t('app', 'Nobody accepts the order online. It may be outdated.');
                }
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $result['msg'] = $e->getMessage();
            //throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $result['msg'] = $e->getMessage();
            //throw $e;
        }

        return $result;
    }
}
