<?php

namespace common\models\shop_order;

use common\models\db\CoWorker;
use common\models\db\CoWorkerCoWorkerFunction;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use common\models\db\ShopOrderUser;
use common\models\db\User;
use frontend\sse\CustomerWaitResponseOrderHandling;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ShopOrderMaker extends ShopOrderWorker
{
    const FOR_ROLES = [
        'maker',
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
            //->select('', 'shop_order_id')
            ->andWhere(['user_id' => $this->workerObj->user_id])
            ->andWhere(['!=', 'type', 'finished'])
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all()
        ) {
            //$orderIds = ArrayHelper::getColumn($newShopOrders, 'shop_order_id');

            $orderTypes = [];
            foreach ($newShopOrders as $ord) {
                $orderTypes[$ord['shop_order_id']] = $ord['type'];
            }
            $orderIds = [];
            foreach ($orderTypes as $shopOrderId => $ordType) {
                if ($ordType == 'offer-accepted-by-maker') {
                    $orderIds[] = $shopOrderId;
                }
            }

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

    public function acceptOrder($orderId)
    {
        $result = ['status' => 'error'];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                $currentTimestamp = date('Y-m-d H:i:s');

                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                $shopOrderStatus->type = 'offer-accepted-by-maker';
                $shopOrderStatus->user_id = $this->workerObj->user_id;
                $shopOrderStatus->accepted_at = $currentTimestamp;
                $shopOrderStatus->accepted_by = $this->workerObj->id;
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                //$shopOrderStatus->save();

                // Установим статусы для других пользователей
                $usersForStatuses = ShopOrderUser::find()
                    ->andWhere(['shop_order_id' => $orderId])
                    ->andWhere(['!=', 'user_id', $this->workerObj->user_id])
                    ->all();
                foreach ($usersForStatuses as $user) {
                    $shopOrderStatus = new ShopOrderStatus();
                    $shopOrderStatus->type = 'offer-blocked-with-other-pizzeria';
                    $shopOrderStatus->user_id = $user->user_id;
                    $shopOrderStatus->accepted_at = $currentTimestamp;
                    $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                }

                if (!$user = User::findOne($this->workerObj->user_id)) {
                    Yii::error('User not found. User id: ' . $this->workerObj->user_id);
                    throw new InternalErrorException('User not found!');
                }

                $acceptedOrderData = [
                    'order_status' => 'accepted-by-merchant',
                    'orderUid' => $shopOrder->order_uid,
                    'merchantData' => [
                        'name' => $user->profile->name,
                        'address' => $user->profile->location,
                        'company_lat_long' => $user->profile->company_lat_long,
                    ],
                ];

                if (CustomerWaitResponseOrderHandling::acceptOrderByMerchant($shopOrder->order_uid,
                    $acceptedOrderData)
                ) {
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
