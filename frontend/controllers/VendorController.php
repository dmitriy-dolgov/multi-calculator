<?php

namespace frontend\controllers;

use yii\web\Response;
use common\models\db\HistoryProfile;
use common\services\HistoryProfileService;
use DeepCopy\Exception\PropertyException;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;


class VendorController extends Controller
{
    public function actionEmpty()
    {
        return '';
    }
}
