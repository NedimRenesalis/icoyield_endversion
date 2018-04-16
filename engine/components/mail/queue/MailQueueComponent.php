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

use app\models\MailAccount;
use yii\swiftmailer\Mailer;
use app\models\MailQueue;
use yii\web\NotFoundHttpException;

/**
 * MailQueueComponent is a sub class of [yii\switmailer\Mailer](https://github.com/yiisoft/yii2-swiftmailer/blob/master/Mailer.php)
 * which intends to replace it.
 *
 * Configuration is the same as in `yii\switmailer\Mailer` with some additional properties to control the mail queue
 *
 * ~~~
 *    'components' => [
 *        ...
 *        'mailQueue' => [
 *            'class' => 'app\components\mail\queue\MailQueueComponent',
 *            'mailsPerRound' => 10,
 *            'maxAttempts' => 3,
 *        ],
 *        ...
 *    ],
 * ~~~
 * Usage
 *      app()->mailQueue->compose()
 *           ->setFrom('from@domain.com')
 *           ->setTo($form->email)
 *           ->setSubject($form->subject)
 *           ->setTextBody($form->body)
 *           ->queue($typeOfTemplate); //type of template from templates system
 *
 * @see http://www.yiiframework.com/doc-2.0/yii-swiftmailer-mailer.html
 * @see http://www.yiiframework.com/doc-2.0/ext-swiftmailer-index.html
 *
 * This extension replaces `yii\switmailer\Message` with `app\components\mail\queue\Message'
 * to enable queuing right from the message.
 *
 */
class MailQueueComponent extends Mailer
{
    /**
     * @var string message default class name.
     */
    public $messageClass = 'app\components\mail\queue\Message';

    /**
     * @var string the name of the database table to store the mail queue.
     */
    protected $table = '{{%mail_queue}}';

    /**
     * @var integer the default value for the number of mails to be sent out per processing round.
     */
    public $mailsPerRound = 10;

    /**
     * @var integer maximum number of attempts to try sending an email out.
     */
    public $maxAttempts = 3;


    /**
     * @var boolean Purges messages from queue after sending
     */
    public $autoPurge = true;

    /**
     * Initializes the MailQueueComponent component.
     */
    public function init()
    {
        parent::init();

        if (app()->db->getTableSchema($this->table) == null) {
            throw new \yii\base\InvalidConfigException('"' . $this->table . '" not found in database. Make sure the db migration is properly done and the table is created.');
        }
    }

    /**
     * Sends out the messages in email queue and update the database.
     *
     * @return boolean true if all messages are successfully sent out
     */
    public function process()
    {
        $success = true;

        // select only with processing =0
        $items = MailQueue::find()->where(['and', ['sent_time' => null], ['<', 'attempts', $this->maxAttempts]])->orderBy(['created_at' => SORT_ASC])->limit($this->mailsPerRound);

        foreach ($items->each() as $item) {

            /* TODO: Avoid race condition on same item.
            if ($item->processing) {
                continue;
            }
            $item->processing = 1;
            $item->save(false);
            */

            /** @var MailQueue $item */

            if ($message = $item->toMessage()) {
                $mailAccount = $this->findMailAccountForTemplateType($item->message_template_type);
                $this->setMailAccount($mailAccount);

                $attributes = ['attempts', 'last_attempt_time'];
                if ($this->send($message)) {
                    $item->sent_time = new \yii\db\Expression('NOW()');
                    $attributes[] = 'sent_time';
                } else {
                    $success = false;
                }

                $item->attempts++;
                $item->last_attempt_time = new \yii\db\Expression('NOW()');

                $item->updateAttributes($attributes);
            }
        }

        // Purge messages now?
        if ($this->autoPurge) {
            $this->purge();
        }

        return $success;
    }


    /**
     * Deletes sent messages from queue.
     *
     * @return int Number of rows deleted
     */

    public function purge()
    {
        return MailQueue::deleteAll('sent_time IS NOT NULL');
    }

    /**
     * Set transport for mail system
     *
     * @param MailAccount $mailAccount
     * @return bool
     */
    protected function setMailAccount(MailAccount $mailAccount)
    {
        return (bool)app()->mailQueue->setTransport([
            'class'      => 'Swift_SmtpTransport',
            'host'       => $mailAccount->hostname,
            'username'   => $mailAccount->username,
            'password'   => $mailAccount->password,
            'port'       => $mailAccount->port,
            'encryption' => $mailAccount->encryption,
            'timeout'    => $mailAccount->timeout ? $mailAccount->timeout : 30,
        ]);
    }

    /**
     * @param $templateType
     * @return \app\models\auto\MailAccount|array|null
     */
    protected function findMailAccountForTemplateType($templateType)
    {
        return MailAccount::find()->andFilterWhere(['like', 'used_for', $templateType])->one();
    }
}
