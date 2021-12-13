<?php

namespace api\modules\setup\controllers;

use common\models\db\User;
use Yii;
use common\models\db\UserVirtual;
use common\models\db\UserVirtualSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserVirtualController implements the CRUD actions for UserVirtual model.
 */
class UserVirtualController extends Controller
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
     * Lists all UserVirtual models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserVirtualSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserVirtual model.
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
     * Creates a new UserVirtual model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserVirtual();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //TODO: сделать лучше, есть код аналогичный
            $newUser = new User();
            $newUser->password_hash = 'no need ' . rand(0, 999999);
            $newUser->email = 'test@email.com' . rand(0, 999999);
            $newUser->username = 'newusername' . rand(0, 999999);
            if ($newUser->save()) {
                $newUserVirtual = new UserVirtual();
                $newUserVirtual->link('user', $newUser);
            }

            return $this->redirect(['view', 'id' => $newUser->id]);
        } else {
            //TODO: обработать как-нибудь
        }

        return $this->render('create', [
            'model' => $model,
            'allUsers' => User::find()->all(),
        ]);

        /*$newUser = new User();
        $newUser->password_hash = 'no need ' . rand(0, 999999);
        $newUser->email = 'test@email.com' . rand(0, 999999);
        $newUser->username = 'newusername' . rand(0, 999999);
        $newUser->save();

        $newUserVirtual = new UserVirtual();
        $newUserVirtual->link('user', $newUser);

        return $this->redirect(['index', 'id' => $newUserVirtual->id]);*/

        $allUsers = User::find()->all();

        if ($newUserVirtual->load(Yii::$app->request->post()) && $newUserVirtual->save()) {
            return $this->redirect(['view', 'id' => $newUserVirtual->id]);
        }

        return $this->render('create', [
            'model' => $newUserVirtual,
            'allUsers' => $allUsers,
        ]);
    }

    /**
     * Updates an existing UserVirtual model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $allUsers = User::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'allUsers' => $allUsers,
        ]);
    }

    /**
     * Deletes an existing UserVirtual model.
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
     * Finds the UserVirtual model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserVirtual the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserVirtual::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
