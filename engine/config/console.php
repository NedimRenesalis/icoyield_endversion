<?php

use kartik\mpdf\Pdf;

Yii::setAlias('@webdir', realpath(dirname(__FILE__) . '/../../'));

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'easyads-console',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@assets' => realpath('../assets'),
        '@webroot' => realpath('../'),
    ],
    'bootstrap' => [
        'log',
        'app\yii\base\Settings',
    ],
    'on beforeRequest' => ['\app\init\Application', 'consoleBeforeRequest'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'defaultRoute' => 'admin',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'options' => [
            'class' => '\twisted1919\options\Options'
        ],
        'pdf' => [
            'mode' => Pdf::MODE_UTF8,
            'class' => Pdf::classname(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
        ],
        'urlManager' => [
                'enablePrettyUrl' => true,
                'rules' => [
                        [
                            'pattern' => 'listing/index/<slug:[a-z0-9_\-]+>',
                            'route' => 'listing/index',
                        ]
                ]
        ],
        'generateInvoicePdf' => [
            'class' => 'app\components\GenerateInvoicePdfComponent',
        ],
        'sendInvoice' => [
            'class' => 'app\components\SendInvoiceComponent',
        ],
        'mailer' => [
            'class' => 'app\yii\swiftmailer\Mailer',
        ],
        'consoleRunner' => [
            'class' => 'vova07\console\ConsoleRunner',
            'file' => '@app/console.php'
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

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

if (is_file($file = __DIR__ . "/console-local.php")) {
    $config = \yii\helpers\ArrayHelper::merge($config, require $file);
}
return $config;
