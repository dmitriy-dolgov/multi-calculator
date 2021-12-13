<?php

namespace api\setup\models\forms\order\status;

use common\models\db\Component;
use common\models\db\ComponentComponentSet;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderComponents;
use common\models\db\ShopOrderStatus;
use common\models\db\ShopOrderUser;
use common\models\db\User;
use Yii;
use yii\base\Model;

class CommonForm extends Model
{
    public $co_worker_id;
    public $comment;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['component_id'], 'integer'],
            [['price', 'price_discount'], 'number'],
            [['name', 'short_name'], 'safe'],
            [
                ['component_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Component::class,
                'targetAttribute' => ['component_id' => 'id']
            ],
        ];
    }

    /**
     * Gets query for [[Component]].
     *
     * @return \yii\db\ActiveQuery|\common\models\db\Component
     */
    public function getComponent()
    {
        return $this->hasOne(Component::class, ['id' => 'component_id']);
    }

    public function save(array $users, array $shopOrderComponents)
    {
        $shopOrder = new ShopOrder();

        for (; ;) {
            //TODO: блокировка таблицы по-хорошему
            //$uid = $user->getId() . '_' . Yii::$app->security->generateRandomString(12);
            $uid = Yii::$app->security->generateRandomString(15);
            if (!ShopOrder::find()->where(['order_uid' => $uid])->exists()) {
                break;
            }
        }

        $shopOrder->order_uid = $uid;
        $shopOrder->created_at = date('Y-m-d H:i:s');
        $shopOrder->deliver_address = $shopOrderComponents['deliver_address'];
        $shopOrder->deliver_customer_name = $shopOrderComponents['deliver_customer_name'];
        $shopOrder->deliver_phone = $shopOrderComponents['deliver_phone'];
        $shopOrder->deliver_email = $shopOrderComponents['deliver_email'];
        $shopOrder->deliver_comment = $shopOrderComponents['deliver_comment'];
        $shopOrder->deliver_required_time_begin = null; //'deliver_required_time_begin - to implement';
        $shopOrder->deliver_required_time_end = null; //'deliver_required_time_end - to implement';
        //$shopOrder->link('user', $user);
        $shopOrder->save();
        foreach ($users as $aUser) {
            $shopOrderUser = new ShopOrderUser();
            //$componentComponentSet->link('componentSet', $componentSet, ['component' => $model]);
            //TODO: разобраться что здесь не так, почему верхний код не работает (это из другого отрывка, здесь возможна подобная ситуация)
            $shopOrderUser->user_id = $aUser->id;
            $shopOrderUser->link('shopOrder', $shopOrder);
        }

        $shopOrderStatus = new ShopOrderStatus();
        $shopOrderStatus->type = 'created';
        $shopOrderStatus->accepted_at = date('Y-m-d H:i:s');
        //$shopOrderStatus->accepted_by = null;
        $shopOrderStatus->link('shopOrder', $shopOrder);

        foreach ($shopOrderComponents['components'] as $componentInfo) {
            if (!$component = Component::findOne($componentInfo['component_id'])) {
                Yii::error('No such component (CommonForm): ' . print_r($componentInfo, true));
                continue;
            }

            //TODO: здесь проверить соответсвие полученных данных о цене и прочим с данными БД

            $shopOrderComponents = new ShopOrderComponents();
            $shopOrderComponents->name = $component->name;
            $shopOrderComponents->short_name = $component->short_name;
            $shopOrderComponents->order_price = $component->price;
            $shopOrderComponents->order_price_discount = $component->price_discount;
            $shopOrderComponents->link('component', $component);
            $shopOrderComponents->link('shopOrder', $shopOrder);
        }

        return $shopOrder;
    }

    /*public function contact($email)
    {
        if ($this->validate()) {

            $subject = 'Message from ' . $_SERVER['HTTP_HOST'] . ': ' . ($this->subject ?: '<no subject>');

            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->email => $this->name])
                ->setSubject($subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }*/
}
