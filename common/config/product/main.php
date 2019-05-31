<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=kung', // MySQL, MariaDB
            'username' => 'root',
            'password' => 'admin123',
            'charset' => 'utf8',
            'tablePrefix' => 'kung_',
        ],

        'uploadTool' => [
            'handler' => [
                'class' => 'common\components\uploadRemote\UploadQiNiu',
                'diskName' => 'privateBucket',
                'qiNiuConfig' => [
                    'class' => 'dcb9\qiniu\Component',
                    'accessKey' => 'c6CfNoc6T9zYITiiINUXQyAx9ojYdNsEcsmUnFbI',
                    'secretKey' => 'UD8CZuJX2I2pZiW1Kznm3wtR9EGAAQO-p_ZLFYUh',
                    'disks' => [
                        'privateBucket' => [
                            'bucket' => 'moviest-com',
                            'baseUrl' => 'http://img.moviest.com/',
                            'isPrivate' => true,
                            'zone' => 'zone1', // 可设置为 zone0, zone1 @see \Qiniu\Zone
                        ],
                    ],
                ],
            ],
        ],
    ],
];