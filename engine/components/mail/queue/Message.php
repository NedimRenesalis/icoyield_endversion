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

namespace app\components\mail\queue;

use app\models\MailQueue;

/**
 * Extends `yii\swiftmailer\Message` to enable queuing.
 *
 * @see http://www.yiiframework.com/doc-2.0/yii-swiftmailer-message.html
 */
class Message extends \yii\swiftmailer\Message
{
    protected $messageTemplateType;


    /**
     * Enqueue the message storing it in database.
     *
     * @param integer $message_template_type
     * @param string $time_to_send
     * @return boolean true on success, false otherwise
     */
    public function queue($message_template_type, $time_to_send = 'now')
    {
        $item = new MailQueue();

        $item->subject = $this->getSubject();
        $item->attempts = 0;
        $item->swift_message = base64_encode(serialize($this));
        $item->time_to_send = new \yii\db\Expression('NOW()');
        $item->message_template_type = $message_template_type;

        return $item->save();
    }
}
