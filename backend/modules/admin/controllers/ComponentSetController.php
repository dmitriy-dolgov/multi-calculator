<?php

namespace backend\modules\admin\controllers;

use common\models\db\ComponentComponentSet;
use Yii;
use common\models\db\ComponentSet;
use common\models\db\ComponentSetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ComponentSetController implements the CRUD actions for ComponentSet model.
 */
class ComponentSetController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ComponentSet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ComponentSetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ComponentSet model.
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
     * Creates a new ComponentSet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ComponentSet();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ComponentSet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ComponentSet model.
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

    public function actionRemoveComponentFromSet()
    {
        $result = false;

        $setId = Yii::$app->request->post('setId');
        $componentId = Yii::$app->request->post('componentId');
        $ajax = Yii::$app->request->post('ajax', false);

        //TODO: проверить корректность данного подхода
        try {
            ComponentComponentSet::deleteAll(['component_set_id' => $setId, 'component_id' => $componentId]);
            $result = true;
        } catch (\Exception $e) {
            $result = false;
            if (!$ajax) {
                Yii::$app->session->setFlash('error', Yii::t('app', "Couldn't remove component from set."));
            }
        }

        if ($ajax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'result' => $result,
            ];
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ComponentSet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ComponentSet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ComponentSet::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
