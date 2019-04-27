<?php
return [
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            'class' => 'common\components\Request',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mob' => [
            'class' => 'common\components\MobileCode',
            'appkey' => '8a93ea9b1b40',
            'api' => 'https://api.sms.mob.com/sms/verify',
        ],
    ],
];