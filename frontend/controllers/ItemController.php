<?php

namespace frontend\controllers;

use common\models\Cart;
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
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'only' => ['beli', 'increment', 'decrement', 'add-to-cart', 'keranjang-show'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
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
            $order->total_payment = $model->price;

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
            $detail->jumlah = 1;
            $detail->subtotal = $model->price;
            $detail->save();



            return $this->redirect(['/customer/show-order']);
        } catch (\Throwable $th) {
            Yii::$app->session->setFlash('error', 'Error' . $th->getMessage());
            return $this->redirect(['/index']);
        }
    }

    public function actionAddToCart($id)
    {
        try {
            $model = $this->findModel($id);
            $cart = new \common\models\Cart();

            $customer = Customer::findOne(['user_id' => Yii::$app->user->id]);
            if ($customer === null) {
                Yii::$app->session->setFlash('error', 'You have no customer');
                return $this->redirect(['/customer/index']);
            }

            $check = Cart::findOne(['customer_id' => $customer->id, 'item_id' => $model->id]);
            if ($check === null) {
                $cart->customer_id = $customer->id;
                $cart->item_id = $model->id;
                $cart->jumlah = 1;
                $cart->save();
            } else {
                $check->jumlah += 1;
                $check->save();
            }
            Yii::$app->session->setFlash('success', "Item added to cart");
            return $this->redirect(['/item/index']);
        } catch (\Throwable $th) {
            Yii::$app->session->setFlash('error', 'Error' . $th->getMessage());
            return $this->redirect(['/index']);
        }
    }

    public function actionCheckout()
    {
        try {

            $customer = Customer::findOne(['user_id' => Yii::$app->user->id]);
            if ($customer === null) {
                Yii::$app->session->setFlash('error', 'You have no customer');
                return $this->redirect(['/customer/index']);
            }
            $carts = $customer->carts;

            if (count($carts) == 0) {
                Yii::$app->session->setFlash('error', 'You have no item in cart');
                return $this->redirect(['/item/keranjang-show']);
            }

            $order = new \common\models\Order();
            $order->date = date("Y-m-d H:i:s");
            $order->customer_id = $customer->id;
            $order->validate();
            $order->save();

            // var_dump($order->save());
            // die;

            $total = 0;
            foreach ($carts as $cart) {
                $detail = new \common\models\OrderItem();
                $detail->item_id = $cart->item->id;
                $detail->order_id = $order->id;
                $detail->harga_pembelian = $cart->item->price;
                $detail->jumlah = $cart->jumlah;
                $detail->subtotal = $cart->jumlah * $cart->item->price;
                $total += $detail->subtotal;
                $detail->save();
            }
            $order->total_payment = $total;
            $order->save();
            Cart::deleteAll(['customer_id' => $customer->id]);
            Yii::$app->session->setFlash('success', "Checkout success");
            return $this->redirect(['/customer/show-order']);
        } catch (\Throwable $th) {
            Yii::$app->session->setFlash('error', 'Error' . $th->getMessage());
            return $this->redirect(['/index']);
        }
    }

    public function actionIncrement($id)
    {

        // var_dump($id);die;
        $customer = Customer::findOne(['user_id' => Yii::$app->user->id]);
        if ($customer === null) {
            Yii::$app->session->setFlash('error', 'You have no customer');
            return $this->redirect(['/customer/index']);
        }
        $model = \common\models\Cart::findOne(["id" => $id, "customer_id" => $customer->id]);
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->jumlah += 1;
        if ($model->validate()) {
            $model->save();
            Yii::$app->session->setFlash('success', "Increment success");
        } else {
            Yii::$app->session->setFlash('error', "Increment failed : " . json_encode($model->getErrors()));
        }
        return $this->redirect(['item/keranjang-show']);
    }

    public function actionDecrement($id)
    {

        $customer = Customer::findOne(['user_id' => Yii::$app->user->id]);
        if ($customer === null) {
            Yii::$app->session->setFlash('error', 'You have no customer');
            return $this->redirect(['/customer/index']);
        }
        $model = \common\models\Cart::findOne(["id" => $id, "customer_id" => $customer->id]);
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->jumlah -= 1;
        if ($model->jumlah <= 0) {

            $model->delete();
        } else {

            if ($model->validate()) {
                $model->save();
                Yii::$app->session->setFlash('success', "Decrement success");
            } else {
                Yii::$app->session->setFlash('error', "Decrement failed : " . json_encode($model->getErrors()));
            }
        }
        return $this->redirect(['item/keranjang-show']);
    }

    public function actionKeranjangShow()
    {
        $customer = Customer::findOne(['user_id' => Yii::$app->user->id]);
        if ($customer === null) {
            Yii::$app->session->setFlash('error', 'You have no customer');
            return $this->redirect(['/customer/index']);
        }
        $carts = $customer->carts;
        return $this->render('keranjang-show', [
            'carts' => $carts,
        ]);
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
