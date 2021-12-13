<?php

namespace api\modules\setup\controllers;

use common\models\db\ShopOrderSignal;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ShopOrderSignalController implements the CRUD actions for ShopOrderSignal model.
 */
class ShopOrderSignalController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        // Создадим таблицу если её еще нет
        if (!$sos = ShopOrderSignal::findOne(['user_id' => Yii::$app->user->getId()])) {
            $sos = new ShopOrderSignal();
            $sos->link('user', Yii::$app->user->identity);
        }

        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () use ($sos) {
                            return ['component' => $sos];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () use ($sos) {
                            return ['component' => $sos];
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('view', [
            'model' => $this->findModel(Yii::$app->user->identity->shopOrderSignal->user_id),
        ]);
    }

    /**
     * Updates an existing ShopOrderSignal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->user->identity->shopOrderSignal->user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['.']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the ShopOrderSignal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopOrderSignal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopOrderSignal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
