<?php

namespace frontend\modules\frontier\controllers;

use yii\web\Controller;

/**
 * Class ApiController
 * @package frontend\modules\frontier\controllers
 */
class ApiController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
