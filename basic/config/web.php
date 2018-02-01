<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'NetAssist',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'homeUrl'=> '/main',
    'defaultRoute' => 'site/main',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'datU8F5ds86M',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
            ],
            'rules' => [
                [
                    'pattern' => 'login',
                    'route' => 'site/login',
                    //'defaults' => ['page' => 1, 'tag' => ''],
                ],
                [
                    'pattern' => 'password-reset',
                    'route' => 'site/request-password-reset',
                    //'defaults' => ['page' => 1, 'tag' => ''],
                ],
                [
                    'pattern' => 'password-change',
                    'route' => 'site/reset-password',
                    //'defaults' => ['page' => 1, 'tag' => ''],
                ],
                [
                    'pattern' => 'registration',
                    'route' => 'site/registration',
                    //'defaults' => ['page' => 1, 'tag' => ''],
                ],
                [
                    'pattern' => 'end-registration',
                    'route' => 'site/end-registration',
                    //'defaults' => ['page' => 1, 'tag' => ''],
                ],
                [
                    'pattern' => 'main',
                    'route' => 'site/main',
                    //'defaults' => ['page' => 1, 'tag' => ''],
                ],
                [
                    'pattern' => 'contact',
                    'route' => 'site/contact',
                    //'defaults' => ['page' => 1, 'tag' => ''],
                ],
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
