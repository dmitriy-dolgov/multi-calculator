<?php

namespace backend\modules\setup\controllers;

use common\models\db\ComponentComponentSet;
use common\models\db\ComponentSet;
use common\models\db\CoWorker;
use common\models\db\CoWorkerCoWorkerFunction;
use common\models\db\CoWorkerSearch;
use common\models\db\CoWorkerFunction;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CoWorkerController implements the CRUD actions for CoWorker model.
 */
class CoWorkerController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['createComponent'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () {
                            return ['component' => CoWorker::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createComponent'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () {
                            return ['component' => CoWorker::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () {
                            return ['component' => CoWorker::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CoWorker models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoWorkerSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['CoWorkerSearch']['user_id'] = Yii::$app->user->getId();
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoWorker model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CoWorker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoWorker();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            try {
                CoWorkerCoWorkerFunction::deleteAll('co_worker_id = :id', [':id' => $model->getPrimaryKey()]);
                $coWorkerFunctionIds = Yii::$app->request->post()['CoWorker']['coWorkerFunctions'];
                if ($coWorkerFunctions = CoWorkerFunction::findAll($coWorkerFunctionIds)) {
                    foreach ($coWorkerFunctions as $cwFunction) {
                        $coWorkerCoWorkerFunction = new CoWorkerCoWorkerFunction();
                        $coWorkerCoWorkerFunction->co_worker_id = $model->getPrimaryKey();
                        $coWorkerCoWorkerFunction->link('coWorkerFunction', $cwFunction);
                    }
                } else {
                    //TODO: обработать ошибку (хотя со временем можем и отказаться от возможности НЕ иметь набор - тогда все нормально)
                }
            } catch (\Exception $e) {
                Yii::error(Yii::t('app', "Couldn't update co-worker functions in the right way. Co-worker ID: {id}", ['id' => $id]));
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $coWorkerFunctions = CoWorkerFunction::find()->all();

        return $this->render('create', [
            'model' => $model,
            'coWorkerFunctions' => $coWorkerFunctions,
        ]);
    }

    /**
     * Updates an existing CoWorker model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            try {
                CoWorkerCoWorkerFunction::deleteAll('co_worker_id = :id', [':id' => $id]);
                $coWorkerFunctionIds = Yii::$app->request->post()['CoWorker']['coWorkerFunctions'];
                if ($coWorkerFunctions = CoWorkerFunction::findAll($coWorkerFunctionIds)) {
                    foreach ($coWorkerFunctions as $cwFunction) {
                        $coWorkerCoWorkerFunction = new CoWorkerCoWorkerFunction();
                        $coWorkerCoWorkerFunction->co_worker_id = $id;
                        $coWorkerCoWorkerFunction->link('coWorkerFunction', $cwFunction);
                    }
                } else {
                    //TODO: обработать ошибку (хотя со временем можем и отказаться от возможности НЕ иметь набор - тогда все нормально)
                }
            } catch (\Exception $e) {
                Yii::error(Yii::t('app', "Couldn't update co-worker functions in the right way. Co-worker ID: {id}", ['id' => $id]));
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $coWorkerFunctions = CoWorkerFunction::find()->all();

        return $this->render('update', [
            'model' => $model,
            'coWorkerFunctions' => $coWorkerFunctions,
        ]);
    }

    /**
     * Deletes an existing CoWorker model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CoWorker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoWorker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoWorker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
