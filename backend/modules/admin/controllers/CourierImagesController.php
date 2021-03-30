<?php

namespace backend\modules\admin\controllers;

use common\models\UploadComponentVideoForm;
use common\models\UploadCourierImageForm;
use Yii;
use common\models\db\CourierImages;
use common\models\db\CourierImagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CourierImagesController implements the CRUD actions for CourierImages model.
 */
class CourierImagesController extends Controller
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
     * Lists all CourierImages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CourierImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CourierImages model.
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
     * Creates a new CourierImages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CourierImages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //TODO: $uploadCourierImageForm => $uploadCourierImageRunForm или разобраться
            $uploadCourierImageForm = new UploadCourierImageForm();
            if ($uploadCourierImageForm->load(Yii::$app->request->post()) && $uploadCourierImageForm->upload($model)) {
                /*$uploadCourierImageForm->imageFile = UploadedFile::getInstances($uploadCourierImageForm, 'imageFile');
                if ($uploadCourierImageForm->upload($model)) {
                    //return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    //TODO: обработать ошибку
                }*/

            } else {
                Yii::error('Ошибка загрузки изображения!');
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'uploadCourierImageForm' => $uploadCourierImageForm,
        ]);
    }

    /**
     * Updates an existing CourierImages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //TODO: $uploadCourierImageForm => $uploadCourierImageRunForm или разобраться
            $uploadCourierImageForm = new UploadCourierImageForm();
            if ($uploadCourierImageForm->load(Yii::$app->request->post()) && $uploadCourierImageForm->upload($model)) {
                /*$uploadCourierImageForm->imageFile = UploadedFile::getInstances($uploadCourierImageForm, 'imageFile');
                if ($uploadCourierImageForm->upload($model)) {
                    //return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    //TODO: обработать ошибку
                }*/

            } else {
                Yii::error('Ошибка загрузки изображения!');
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'uploadCourierImageForm' => $uploadCourierImageForm,
        ]);
    }

    /**
     * Deletes an existing CourierImages model.
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
     * Finds the CourierImages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourierImages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourierImages::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
