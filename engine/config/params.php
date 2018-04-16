<?php
$params =  [
    'adminEmail' => '',
    'adminUrl'   => '/admin',
];


if (is_file($file = __DIR__ ."/params-local.php")) {
    $params = \yii\helpers\ArrayHelper::merge($params, require $file);
}
return $params;
