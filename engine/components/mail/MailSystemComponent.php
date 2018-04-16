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

namespace app\components\mail;

use yii\base\Component;
use yii\web\NotFoundHttpException;
use app\models\MailTemplate;
use app\models\MailAccount;

/**
 * Class EmailQueueComponent
 * @package app\components
 */
class MailSystemComponent extends Component
{
    /**
     * Generate and add message to the queue
     *
     * @param       $templateName
     * @param array $data
     * @return bool
     */
    public function add($templateName, array $data = [])
    {
        $mailTemplate = $this->findTemplateBySlug($templateName);
        $mailAccount = $this->findMailAccountForTemplateType($mailTemplate->template_type);

        if ($mailAccount === null){
            return;
        }

        $result = app()->twigTemplate->generateMessage($mailTemplate, $data);

        /**
         * @var \app\components\mail\queue\Message $message
         */
        if ($mailTemplate->isPlainContent) {
            $message = app()->mailQueue->compose();
            $message->setHtmlBody(strip_tags($result['message'], '<br><p>'));
        } else {
            $message = app()->mailQueue->compose('content-wrapper', ['message' => $result['message']]);
        }


        // attachments
        if (!empty($data['attachContent'])) {
            $message->attachContent($data['attachContent']['file'], $data['attachContent']['fileMeta']);
        }
        $message->setSubject($mailTemplate->subject);

        // mail account settings
        $message->setFrom($mailAccount->from);

        if (!empty($data['reply_to'])) {
            $message->setReplyTo($data['reply_to']);
        } else if ($mailAccount->reply_to) {
            $message->setReplyTo($mailAccount->reply_to);
        }

        if (!empty($data['recipient'])) {
            $message->setTo($data['recipient']);
        } else {
            $message->setTo($result['recipient']);
        }

        return (bool)$message->queue($mailTemplate->template_type);
    }

    /**
     * Find template of email by unique slug
     *
     * @param $slug
     *
     * @return MailTemplate
     * @throws NotFoundHttpException
     */
    protected function findTemplateBySlug($slug)
    {
        if (($template = MailTemplate::findOne(['slug' => $slug])) !== null) {
            return $template;
        }

        throw new NotFoundHttpException(t('app', 'The requested template does not exist.'));
    }

    /**
     * Find mail account by template type
     *
     * @param $templateType
     * @return \app\models\auto\MailAccount|array|null
     */
    protected function findMailAccountForTemplateType($templateType)
    {
        return MailAccount::find()->andFilterWhere(['like', 'used_for', $templateType])->one();
    }

    /**
     * @return int|string
     */
    public function getAccounts()
    {
        return MailAccount::find()->count();
    }
}