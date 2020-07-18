<?php

namespace backend\modules\setup\controllers;

use backend\modules\vendor\models\Vendor;
use yii\web\Controller;

/**
 * Default controller for the `setup` module
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                //'view' => '@yiister/gentelella/views/error',
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLinkToEmbed()
    {
        return $this->render('link-to-embed', [
            'orderHtml' => (new Vendor())->getHtmlElementToEmbed(['class' => 'frame-example']),
        ]);
    }
}
