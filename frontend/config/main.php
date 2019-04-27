<?php
return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'layout' => false,
    'components' => [
//        'user' => [
//            'identityClass' => 'common\models\mysql\UserModel',
//            'idParam' => 'zuiying_frontend_id',
//            'identityCookie' => ['name' => 'zuiying_frontend_identity', 'httpOnly' => true],
//            'enableAutoLogin' => true,
//        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'authManager' => [
            'class' => 'common\components\rabc\DbManager',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.json',//后缀
        ],

        'request' => [
            'class' => 'common\components\Request',
            'cookieValidationKey' => '1234567890qwertyuioasdfgh',
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'config' => [
            'ConfigPaths' => ['@backend'],
        ],
    ],
    'params' => \common\helpers\CommonHelper::loadConfig('params',['@frontend']),
];