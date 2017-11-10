<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager'=>[
            //RBAC
            'class'=>\yii\rbac\DbManager::className(),
        ]
    ],
];
