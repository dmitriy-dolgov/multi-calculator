<?php

namespace console\controllers;

use common\models\repositories\UserRepository;
use yii\base\Controller;


/**
 * Класс для теста.
 * Применяется для любых тестовыйх операций, напр. консольных приложений.
 */
class TestController extends Controller
{
    public function actionIndex()
    {
        UserRepository::createFakeUsers();
    }
}
