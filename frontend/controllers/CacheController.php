<?php

namespace frontend\controllers;

use yii\web\Controller;

class CacheController extends Controller
{
    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent[] =
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60
            ];

        $parent[] = [
            'class' => 'yii\filters\HttpCache',
            'only' => ['index'],'lastModified' => function ($action, $params) {
                return time();
            },        
            'etagSeed' => function ($action, $params) {
                $user = \common\models\User::findOne(\Yii::$app->request->get('id'));
                if ($user == null) return null;
                return serialize([$user->username, $user->email]);
            },
            'sessionCacheLimiter' => 'public'
        ];
        return $parent;
    }

    public function actionHttpCache() {
        var_dump($_COOKIE);
        die;
    }

    public function actionIndex()
    {
        return $this->render('../site/index');
    }

    public function actionDataCache()
    {
        $cache = \Yii::$app->cache;
        // try retrieving $data from cache
        $data = $cache->get("data_cache");
        if ($data === false) {
            // $data is not found in cache, calculate it from scratch
            $data = date("d.m.Y H:i:s");
            // store $data in cache so that it can be retrieved next time
            $cache->set("data_cache", $data, 30);
        } else {
            // $data was found in cache!
            echo "Data from cache: $data";
        }
        // $data is available here
        var_dump($data);
    }

    public function actionFragmentCaching()
    {
        $models = \common\models\User::find()->all();
        return $this->render('cacheview', ['models' => $models]);
    }
}
