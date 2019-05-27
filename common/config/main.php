<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'debug' => 'yii\debug\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'kung_',
        ],

        'config' => [
            'class' => 'common\components\Config',
            'ConfigPaths' => ['@common'],
        ],

        'trans' => [
            'class' => 'common\components\Translation',
            'key' => 'AIzaSyC0caXMqhMV0Fg51x9mJMxOjVpLhhrK2lo',
        ],

        'lang' => [
            'class' => 'common\components\Lang',
        ],

        'uploadTool' => [
            'class' => 'common\components\uploadRemote\UploadTool',
        ],

        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset'=>[
                    'js' => []
                ]
            ]
        ],
    ],
    'params' => \common\helpers\CommonHelper::loadConfig('params',['@common']),
];