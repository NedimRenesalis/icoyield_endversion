<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0
 */


namespace app\yii\swiftmailer;

use yii\swiftmailer\Mailer as BaseMailer;

/**
 * Class Mailer
 * @package app\yii\swiftmailer
 */
class Mailer extends BaseMailer
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        /* call parent implementation */
        parent::init();
        
        /* see if we need to set our transport */
        $hostname   = options()->get('app.settings.email.hostname', '');
        $username   = options()->get('app.settings.email.username', '');
        $password   = options()->get('app.settings.email.password', '');
        $port       = options()->get('app.settings.email.port', 25);
        $encryption = options()->get('app.settings.email.encryption', '');
        $timeout    = options()->get('app.settings.email.timeout', 30);
        
        if ($hostname && $username && $password) {
            $this->setTransport([
                'class'      => 'Swift_SmtpTransport',
                'host'       => $hostname,
                'username'   => $username,
                'password'   => $password,
                'port'       => $port,
                'encryption' => $encryption,
                'timeout'    => $timeout,
            ]);
        }
    }
}