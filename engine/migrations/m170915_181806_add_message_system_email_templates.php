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

use yii\db\Migration;

class m170915_181806_add_message_system_email_templates extends Migration
{
    public function up()
    {
        // create initial list of templates.
        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 9;
        $template->template_type    = \app\components\mail\template\TemplateTypeAdConversation::TEMPLATE_TYPE;
        $template->name             = 'New conversation';
        $template->subject          = 'New conversation about your listing';
        $template->content          = '<p>Hello, {{recipient_name}}!</p><p>You have unread messages about the listing <strong>{{ad_title}}</strong></p><p>{{sender_name}} has sent you the following message:</p><p>{{message |raw}}</p><p><a href="{{reply_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Reply</a><br /><br /><br /><span style="color:#000000">This email was sent to {{recipient_email}}.</span></p>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 10;
        $template->template_type    = \app\components\mail\template\TemplateTypeAdConversation::TEMPLATE_TYPE;
        $template->name             = 'You have unread messages';
        $template->subject          = 'You have unread messages about your listing';
        $template->content          = '<p>Hello, {{recipient_name}}!</p><p>You have unread messages about the listing <strong>{{ad_title}}</strong></p><p>{{sender_name}} has sent you the following message:</p><p>{{message |raw}}</p><p><a href="{{reply_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Reply</a><br /><br /><br /><span style="color:#000000">This email was sent to {{recipient_email}}.</span></p>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 11;
        $template->template_type    = \app\components\mail\template\TemplateTypeAdConversation::TEMPLATE_TYPE;
        $template->name             = 'Non-user message';
        $template->subject          = 'New message about your listing';
        $template->content          = '<p>Hello, {{recipient_name}}!</p><p>You have new email message about the listing <strong>{{ad_title}}</strong></p><p>{{sender_name}} has sent you the following message:</p><p>{{message |raw}}</p><p>Please contact {{sender_name}} by replying directly to this email.</p><p><span style="color:#000000">This email was sent to {{recipient_email}}.</span></p>';
        $template->save();
    }

    public function down()
    {
        return \app\models\MailTemplate::deleteAll(['template_id' => [9,10,11]]);
    }
}
