<?php

namespace backend\modules\admin\controllers;

use common\models\db\Component;
use common\models\db\ComponentComponentSet;
use common\models\db\ComponentImage;
use common\models\db\ComponentSearch;
use common\models\db\ComponentSet;
use common\models\db\Unit;
use common\models\UploadComponentImageForm;
use common\models\UploadComponentVideoForm;
use common\models\UploadForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * ComponentController implements the CRUD actions for Component model.
 */
class ComponentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                //TODO: image-delete - разобраться чтобы удаляло только для пользователя
                'only' => ['index', 'view', 'create', 'update', 'image-delete', 'delete'],
                /*'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['client'],
                    ],
                ],*/
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        //'roles' => ['manageComponent'],
                        'roles' => ['createComponent'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        //'roles' => ['viewComponent'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () {
                            return ['component' => Component::findOne(['id' => Yii::$app->request->get('id')])];
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
                            return ['component' => Component::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        //'roles' => ['deleteComponent'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () {
                            return ['component' => Component::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['image-delete'],
                        'roles' => ['updateComponent'],
                        'roleParams' => function () {
                            return ['component' => Component::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Component models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ComponentSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['ComponentSearch']['user_id'] = null;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Component model.
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
     * Creates a new Component model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new Component();
        $componentImage = new ComponentImage();
        $uploadedImageRelativePaths = [];

        if (Yii::$app->request->isPost) {
            $files = UploadedFile::getInstances($componentImage, 'relative_path');
            $uploadedImageRelativePaths = $componentImage->upload($files);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($uploadedImageRelativePaths) {
                foreach ($uploadedImageRelativePaths as $imageRelativePath) {
                    $imageObj = new ComponentImage();
                    $imageObj->relative_path = $imageRelativePath;
                    $imageObj->link('component', $model);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'componentImage' => $componentImage,
        ]);
    }*/

    public function actionCreate()
    {
        $model = new Component();
        $uploadImageForm = new UploadComponentImageForm();
        $uploadVideoForm = new UploadComponentVideoForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //TODO: КОСТЫЛЬ, проверить что не так (BlamebleBehaviour)
            $command = Yii::$app->db->createCommand('UPDATE `component` SET `user_id` = NULL WHERE `id` = :id', [':id' => $model->getPrimaryKey()]);
            $command->execute();

            $id = $model->getPrimaryKey();

            ComponentComponentSet::deleteAll('component_id = :id', [':id' => $id]);
            $componentSetId = Yii::$app->request->post()['Component']['componentSets'];
            if ($componentSets = ComponentSet::findAll($componentSetId)) {
                foreach ($componentSets as $compSet) {
                    $componentComponentSet = new ComponentComponentSet();
                    //$componentComponentSet->link('componentSet', $componentSet, ['component' => $model]);
                    //TODO: разобраться что здесь не так, почему верхний код не работает
                    $componentComponentSet->component_id = $id;
                    $componentComponentSet->link('componentSet', $compSet);
                }
            } else {
                //TODO: обработать ошибку (хотя со временем можем и отказаться от возможности НЕ иметь набор - тогда все нормально)
            }

            $uploadImageForm->imageFiles = UploadedFile::getInstances($uploadImageForm, 'imageFiles');
            if ($uploadImageForm->upload($model)) {
                //return $this->redirect(['view', 'id' => $model->id]);
            } else {
                //TODO: обработка ошибки
            }

            $uploadVideoForm->videoFiles = UploadedFile::getInstances($uploadVideoForm, 'videoFiles');
            if ($uploadVideoForm->upload($model)) {
                //return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $modelComponentSet = ComponentSet::find()->all();
        $modelUnits = Unit::find()->all();

        return $this->render('create', [
            'model' => $model,
            'modelComponentSet' => $modelComponentSet,
            'modelUnits' => $modelUnits,
            'uploadImageForm' => $uploadImageForm,
            'uploadVideoForm' => $uploadVideoForm,
        ]);
    }

    /**
     * Updates an existing Component model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $uploadImageForm = new UploadComponentImageForm();
        $uploadVideoForm = new UploadComponentVideoForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //TODO: КОСТЫЛЬ, проверить что не так (BlamebleBehaviour)
            $command = Yii::$app->db->createCommand('UPDATE `component` SET `user_id` = NULL WHERE `id` = :id', [':id' => $model->getPrimaryKey()]);
            $command->execute();

            ComponentComponentSet::deleteAll('component_id = :id', [':id' => $id]);
            $componentSetId = Yii::$app->request->post()['Component']['componentSets'];
            if ($componentSets = ComponentSet::findAll($componentSetId)) {
                foreach ($componentSets as $compSet) {
                    $componentComponentSet = new ComponentComponentSet();
                    //$componentComponentSet->link('componentSet', $componentSet, ['component' => $model]);
                    //TODO: разобраться что здесь не так, почему верхний код не работает
                    $componentComponentSet->component_id = $id;
                    $componentComponentSet->link('componentSet', $compSet);
                }
            } else {
                //TODO: обработать ошибку (хотя со временем можем и отказаться от возможности НЕ иметь набор - тогда все нормально)
            }

            $uploadImageForm->imageFiles = UploadedFile::getInstances($uploadImageForm, 'imageFiles');
            if ($uploadImageForm->upload($model)) {
                //return $this->redirect(['view', 'id' => $model->id]);
            } else {
                //TODO: обработать ошибку
            }

            $uploadVideoForm->videoFiles = UploadedFile::getInstances($uploadVideoForm, 'videoFiles');
            if ($uploadVideoForm->upload($model)) {
                //return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $modelComponentSet = ComponentSet::find()->all();
        $modelUnits = Unit::find()->all();

        return $this->render('update', [
            'model' => $model,
            'modelComponentSet' => $modelComponentSet,
            'modelUnits' => $modelUnits,
            'uploadImageForm' => $uploadImageForm,
            'uploadVideoForm' => $uploadVideoForm,
        ]);
    }

    public function actionImageDelete($id)
    {
        if (!$imageDb = ComponentImage::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('app', 'The image does not exist.'));
        }

        $imageDb->delete();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return json_encode(true);
    }

    /**
     * Deletes an existing Component model.
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

    /*public function actionFileUpload()
    {
//        $uploaddir = Yii::getAlias('@webroot' . Yii::$app->params['component_images']['url_path']);
//        $uploadfile = $uploaddir . basename($_FILES['componentImages']['name'][0]);
//
//        move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);

        $model = new Component();
        //$uploadModel = new UploadForm();
        if (Yii::$app->request->isPost) {
            //$model->load(Yii::$app->request->post());
            $model->imageFiles = UploadedFile::getInstance($model, 'imageFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }
    }*/

    /**
     * Finds the Component model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Component the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Component::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
