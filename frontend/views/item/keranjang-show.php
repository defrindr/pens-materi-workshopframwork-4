<?php

// var_dump($carts);
// die;

use yii\bootstrap4\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
$total = 0;
?>
<div class="item-index">
    <div class="card card-default">
        <div class="card-body">
            <?php if (count($carts) == 0) : ?>
                Data keranjang kosong
            <?php else : ?>
                <table class="table table-bordered table-hov">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carts as $key => $cart) : $total += ($cart->jumlah * $cart->item->price); ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= $cart->item->name ?></td>
                                <td><?= $cart->jumlah ?></td>
                                <td><?= $cart->item->category->name ?></td>
                                <td><?= $cart->item->price ?></td>
                                <td>
                                    <a href="<?= Url::to(['item/increment', 'id' => $cart->id]) ?>" class="btn btn-primary btn-sm mr-1">+</a>
                                    <a href="<?= Url::to(['item/decrement', 'id' => $cart->id]) ?>" class="btn btn-danger btn-sm mr-1">-</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <tr>
                            <td colspan="4">Total Harga</td>
                            <td>
                                Rp <?= $total ?>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <?= Html::a('Checkout', ['checkout'], ['class' => 'btn btn-primary']) ?>
            <?php endif ?>
        </div>
    </div>

</div>