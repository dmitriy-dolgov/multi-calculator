<?php

namespace app\modules\vendor\models;

use Yii;
use app\models\db\Profile;
use yii\bootstrap\Html;
use yii\helpers\Url;

class Vendor
{
    public function getProfileForOrderPage($url, $test)
    {
        if ($test) {
            $urlParsed = parse_url($url);
            if (!isset($urlParsed['host']) || ($_SERVER['SERVER_NAME'] != $urlParsed['host'])) {
                // Взлом?
                echo 'No test';
                Yii::error('URL without host: ' . $url);
                Yii::$app->end();
            }

            if (Yii::$app->user->isGuest) {
                echo Yii::t('app', 'Please sign in.');
                Yii::$app->end();
            }

            $profile = Yii::$app->user->identity->profile;
        } else {
            $profile = (new Profile())->getProfileByUrl($url);
        }

        return $profile;
    }

    public function getUrlToOrder()
    {
        $url = Url::to(['/vendor/order/' . Yii::$app->user->identity->getOrderUid()], true);

        return $url;
    }

    public function getHtmlElementToEmbed(array $options = [])
    {
        $options['src'] = $this->getUrlToOrder();

        return Html::tag('iframe', '', $options);
    }
}
