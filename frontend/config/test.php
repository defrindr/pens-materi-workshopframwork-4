<?php
return [
    'id' => 'app-frontend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
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
               
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'baseUrl' => '/',
        ],
    ],
    "defaultRoute" => "item/index",
];
