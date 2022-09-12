<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Frontend Toko',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        // 'cache' => [
        //     'class' => 'yii\caching\FileCache',
        // ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '/mymart2',
            'parsers' => [
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
                'application/json' => 'yii\web\JsonParser', // enable json parser
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $path_info = \Yii::$app->request->pathInfo;
                // var_dump(strpos($path_info, "item/view"));die;
                if (strpos($path_info, "item/index") !== false || strpos($path_info, "item/view") !== false) {
                    $log = new \common\models\Statistics();
                    $log->user_ip = \Yii::$app->request->userIP ?? "0.0.0.0";
                    $log->user_host = \Yii::$app->request->hostInfo;
                    $log->access_time = date("Y-m-d H:i:s");
                    $log->path_info = \Yii::$app->request->pathInfo;
                    $log->query_string = \Yii::$app->request->queryString;
                    if ($log->validate()) {
                        $log->save();
                    } else {
                        var_dump($log->errors);
                        die;
                    }
                }
            },
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',   
                    'tokens' => [
                         '{id}' => '<id:\\w+>'
                    ],
               ],
               [
                   'class' => 'yii\rest\UrlRule',
                   'controller' => 'api/item',   
                   'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                ],
                '<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
               
            ],
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://localhost/mymart2/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
    'params' => $params,
    "defaultRoute" => "item/index",
];
