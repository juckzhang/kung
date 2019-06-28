<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=kung', // MySQL, MariaDB
            'username' => 'root',
            'password' => 'admin123',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'kung_',
        ],

        'uploadTool' => [
            'uploadTool' => [
                'class' => 'juckzhang\drivers\UploadTool',
                'handler' => [
                    'class' => 'juckzhang\drivers\UploadAliYun',
                    'accessKeyId' => 'LTAIhqAEiHvZxEs3',
                    'accessKeySecret' => 'HZnqx1EnrjLv4WZCUNNOoqx4NjHRkS',
                    'bucket' => 'kongchinese1',
                    'endPoint' => 'kongchinese1.oss-cn-hongkong.aliyuncs.com/',
                ],
            ],
        ],
    ],
];