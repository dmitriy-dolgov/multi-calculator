<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\widgets\shopOrders\ShopOrders;
use common\models\db\Category;
use common\models\db\User;
use Da\User\Controller\AdminController as BaseController;
use Da\User\Search\UserSearch;
use Yii;
use yii\web\NotFoundHttpException;

class OrderController extends BaseController
{
    public function actionListUserWorkerRelated()
    {
        $searchModel = $this->make(UserSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list-user-worker-related', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListUserRelated()
    {
        //$searchModel = ShopOrder::;
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModel = $this->make(UserSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list-user-related', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOrdersByUser()
    {
        //admin/order/orders-by-user

        if (!$userId = Yii::$app->request->post('expandRowKey')) {
            throw new NotFoundHttpException('Not found');
        }

        return ShopOrders::widget(['modelUser' => User::getOrFail($userId)]);
        //return $this->renderPartial('_orders-for-user', compact('modelUser'));
    }

    public function actionOrdersWorkersByUser()
    {
        //admin/order/orders-workers-by-user

        //TODO: реализовать, пока хардков во вью
        if (!$userId = Yii::$app->request->post('expandRowKey')) {
            throw new NotFoundHttpException('Not found');
        }

        return $this->renderPartial('_orders-workers-for-user');
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
//    protected function findModel($id)
//    {
//        if (($model = Order::findOne($id)) !== null) {
//            return $model;
//        }
//
//        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
//    }
}
