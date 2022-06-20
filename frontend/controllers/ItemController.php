<?php

namespace frontend\controllers;

use common\models\Customer;
use common\models\Item;
use common\models\search\ItemSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

// defrindr

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionBeli($id)
    {
        try {
            $model = $this->findModel($id);
            $order = new \common\models\Order();
            $order->date = date("Y-m-d H:i:s");

            $customer = Customer::findOne(['user_id' => Yii::$app->user->id]);
            if ($customer === null) {
                Yii::$app->session->setFlash('error', 'You have no customer');
                return $this->redirect(['/customer/index']);
            }

            $order->customer_id = $customer->id;

            $order->save();

            $detail = new \common\models\OrderItem();
            $detail->item_id = $model->id;
            $detail->order_id = $order->id;
            $detail->save();

            return $this->redirect(['/customer/show-order']);
        } catch (\Throwable $th) {
            Yii::$app->session->setFlash('error', 'Error' . $th->getMessage());
            return $this->redirect(['/index']);
        }
    }

    /**
     * Lists all Item models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination = [
            'pageSize' => 9,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
