<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => '{DB_CONNECTION_STRING}',
    'username' => '{DB_USER}',
    'password' => '{DB_PASS}',
    'charset' => 'utf8',
    'tablePrefix'=> '{DB_PREFIX}',
    'on afterOpen' => function($event) {
        $event->sender->createCommand('SET time_zone="+00:00"')->execute();
        $event->sender->createCommand('SET NAMES utf8')->execute();
        $event->sender->createCommand('SET SQL_MODE=""')->execute();
    },
];