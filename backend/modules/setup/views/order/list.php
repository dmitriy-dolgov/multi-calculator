<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $orderList common\models\db\ShopOrder */

$this->title = Yii::t('app', 'Shop orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-worker-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

    foreach ($orderList as $ord) {
        echo 'UID: ' . $ord->order_uid . '<br>';
        echo 'Создан: ' . $ord->created_at . '<br>';
        echo 'Адрес доставки: ' . $ord->deliver_address . '<br>';
        echo 'Имя получателя: ' . $ord->deliver_customer_name . '<br>';
        echo 'Телефон получателя: ' . $ord->deliver_phone . '<br>';
        echo 'Email получателя: ' . $ord->deliver_email . '<br>';

        echo '<br>';
    }

    ?>


</div>
