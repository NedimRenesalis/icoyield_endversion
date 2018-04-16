<?php

use kartik\mpdf\Pdf;

Yii::setAlias('@webdir', realpath(dirname(__FILE__) . '/../../'));

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'EasyAds',
    'name' => 'EasyAds',
    'basePath' => dirname(__DIR__),
    'timeZone' => 'UTC',
    'aliases' => [
        '@assets' => '../../assets',
    ],
    'bootstrap' => [
        'log',
        'app\modules\admin\yii\base\AdminBootstrapInterface',
        'app\yii\base\Settings',
    ],
    'on beforeRequest' => ['\app\init\Application', 'webBeforeRequest'],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'defaultRoute' => 'admin',
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ],
    ],
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                ],
                'linkedin' => [
                    'class' => 'yii\authclient\clients\LinkedIn',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'attributeParams' => [
                     'include_email' => true
                 ],
                ]
            ],
        ],
        'request' => require(__DIR__ . '/request.php'),
        'db' => require(__DIR__ . '/db.php'),
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'identityCookie' => ['name' => '_identity_user'],
            'loginUrl' => ['/admin'],
        ],
        'customer' => [
            'class' => 'app\yii\web\Customer',
            'identityClass' => 'app\models\Customer',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'loginUrl' => ['/account/login'],
            'identityCookie' => ['name' => '_identity_customer'],
            'idParam' => '__customer_id',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'app\yii\swiftmailer\Mailer',
        ],
        'httpClient' => [
            'class' => 'yii\httpclient\Client',
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
        'options' => [
            'class' => '\twisted1919\options\Options'
        ],
        'notify' => [
            'class' => '\twisted1919\notify\Notify',
        ],
        'assetManager' => [
            'baseUrl' => '@web/assets/cache',
            'basePath' => '@webroot/assets/cache',
            'linkAssets' => false,
            'appendTimestamp' => true,
            'assetMap' => [
                'jquery.js' => 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => false,
            'rules' => [
                'search' => 'site/search',
                'contact' => 'site/contact',
                '<controller:conversation>/<action:delete>' => '<controller>/<action>',
                '<controller:conversation>/<action:reply>/<conversation_uid:[a-z0-9_\-]+>' => '<controller>/<action>',
                '<controller:account>/<action:invoices>/<page:\d+>' => '<controller>/<action>',
                '<controller:account>/<action:conversations>/<page:\d+>' => '<controller>/<action>',
                '<controller:listing>/<action:index|update|package|preview>/<slug:[a-z0-9_\-]+>' => '<controller>/<action>',
                'page/<slug:[a-z0-9_\-]+>' => 'pages/index',
                '<controller:category>/<action:location|map-view|get-map-location>' => '<controller>/<action>',
                [
                    'pattern' => 'category/<slug:[a-z0-9_\-]+>/<page:\d+>',
                    'route' => 'category/index',
                ],
                [
                    'pattern' => 'category/<slug:[a-z0-9_\-]+>',
                    'route' => 'category/index',
                ],
                [
                    'pattern' => 'category/map-view/<slug:[a-z0-9_\-]+>/<page:\d+>',
                    'route' => 'category/map-view',
                ],
                [
                    'pattern' => 'category/map-view/<slug:[a-z0-9_\-]+>',
                    'route' => 'category/map-view',
                ],
                [
                    'pattern' => 'store/<slug:[a-z0-9_\-]+>/<page:\d+>',
                    'route' => 'store/index',
                ],
                [
                    'pattern' => 'store/<slug:[a-z0-9_\-]+>',
                    'route' => 'store/index',
                ],
                '<url:.+/>' => 'site/redirect'
            ],
        ],
        'formatter' => [
            'decimalSeparator' => '.',
        ],
        'pdf' => [
            'mode' => Pdf::MODE_UTF8,
            'class' => Pdf::classname(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
        ],
        'generateInvoicePdf' => [
            'class' => 'app\components\GenerateInvoicePdfComponent',
        ],
        'sendInvoice' => [
            'class' => 'app\components\SendInvoiceComponent',
        ],
        'mailQueue' => [
            'class' => 'app\components\mail\queue\MailQueueComponent',
        ],
        'twigTemplate' => [
            'class' => 'app\components\mail\template\MailTemplateComponent',
        ],
        'mailSystem' => [
            'class' => 'app\components\mail\MailSystemComponent',
        ],
        'migration' => [
            'class' => 'twisted1919\helpers\Migration',
        ],
    ],
    'params' => $params,
];

if (YII_ENV != 'production') {
    // configuration adjustments for 'dev' environments
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*']
    ];

    // to clear asset cache
    $config['components']['assetManager']['forceCopy'] = true;
}


if (is_file($file = __DIR__ . "/web-local.php")) {
    $config = \yii\helpers\ArrayHelper::merge($config, require $file);
}
return $config;
