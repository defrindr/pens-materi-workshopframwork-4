<?php

namespace frontend\controllers;

use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\httpclient\Client;

class WebClientController extends Controller
{
    public function actionIndex()
    {
        $client = new Client(['baseUrl' => 'http://localhost/mymart2/']);
        $response = $client->createRequest()
            ->setUrl('api/users')
            ->addHeaders(['content-type' => 'application/json'])
            ->send();
        $data = Json::decode($response->content);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
