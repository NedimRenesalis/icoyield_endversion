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

/**
 * Handles the creation of table `mail_template`.
 */
class m170428_164623_create_mail_template_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%mail_template}}', [
            'template_id'           => $this->primaryKey(),
            'template_type'         => $this->smallInteger(5)->unsigned()->notNull(),
            'name'                  => $this->string(80)->notNull()->unique(),
            'slug'                  => $this->string(80)->notNull()->unique(),
            'subject'               => $this->string(80)->notNull(),
            'isPlainContent'        => $this->boolean()->unsigned()->defaultValue(0),
            'content'               => $this->text()->notNull(),
            'created_at'            => $this->dateTime()->notNull(),
            'updated_at'            => $this->dateTime()->notNull(),
        ], $tableOptions);

        // create initial list of templates.
        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 1;
        $template->template_type    = \app\components\mail\template\TemplateTypeAd::TEMPLATE_TYPE;
        $template->name             = 'Ad is active';
        $template->subject          = 'Your ad is active now';
        $template->content          = '<h1>Your Ad is Active</h1><font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">Hello there, {{customer_name}}<br /><br />Thank you for posting your ad "{{ad_title}}" on {{general_site_name}}.<br /><br />Your Ad is active now in our system, and will be visible in no time.</font><br /><br /><br /><a href="{{ad_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Preview your ad now</a><br /><br /><br /><br /><font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">This email was sent to {{customer_email}}.</font>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 2;
        $template->template_type    = \app\components\mail\template\TemplateTypeInvoice::TEMPLATE_TYPE;
        $template->name             = 'Customer invoice';
        $template->subject          = 'Invoice Details';
        $template->content          = '<h1>Invoice Details</h1><p> <span style="color:#000000"> Hello there, {{customer_first_name}} <br /><br /> Your invoice is attached <br /></span> <br /><br /> <span style="color:#000000"> This email was sent to {{customer_email}}. </span></p>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 3;
        $template->template_type    = \app\components\mail\template\TemplateTypeAd::TEMPLATE_TYPE;
        $template->name             = 'Ad is deactivated';
        $template->subject          = 'Your ad is deactivated';
        $template->content          = '<h1>Your Ad is deactivated</h1> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">Hello there, {{customer_name}}     <br /><br /> Thank you for posting your ad "{{ad_title}}" on {{general_site_name}}.     <br /><br /> unfortunately there seems to be a problem with your Ad, please contact us {{general_contact_email}} to find a solution together. </font> <br /><br /> <br /><br /> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000"> This email was sent to {{customer_email}}. </font>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 4;
        $template->template_type    = \app\components\mail\template\TemplateTypeAd::TEMPLATE_TYPE;
        $template->name             = 'Ad is waiting approval';
        $template->subject          = 'Your ad is being reviewed';
        $template->content          = '<h1>Your Ad is waiting approval</h1> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">Hello there, {{customer_name}}     <br /><br /> Thank you for posting your ad "{{ad_title}}" on {{general_site_name}}.     <br /><br /> Your ad is being reviewed right now and we will get back to you with a confirmation email once we activate it.     <br /><br /> If you have any questions please contact us {{general_contact_email}} </font> <br /><br /> <br /><br /> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000"> This email was sent to {{customer_email}}. </font>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 5;
        $template->template_type    = \app\components\mail\template\TemplateTypeAdmin::TEMPLATE_TYPE;
        $template->name             = 'Admin forgot password';
        $template->subject          = 'Admin Password change request';
        $template->content          = '<h1>Admin Password change request</h1><p> <span style="color:#000000"> Hello there, {{admin_first_name}}<br /><br /> You have requested a password reset. Please follow the link below to update your password. </span> <br /><br /> <a href="{{confirmation_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Click here to edit your password</a> <br /><br /> <span style="color:#000000"> If the link above doesn\'t work, try copying the following url into your browser:<br />  <a href="#" style="color: #c06014; text-decoration: none;">{{confirmation_url}}</a> </font> <br /><br /> <font face="> This email is sent to you because somebody (hopefully you) requested a password change for your account. If you did not request a new password, please forgive us and ignore this message. </a></span></p>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 6;
        $template->template_type    = \app\components\mail\template\TemplateTypeCustomer::TEMPLATE_TYPE;
        $template->name             = 'Customer password change request';
        $template->subject          = 'Password Change Request';
        $template->content          = '<h1>Password change request</h1> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">Hello there, {{customer_first_name}}     <br /><br /> You have requested a password reset. Please follow the link below to update your password. </font> <br /><br /> <a href="{{confirmation_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Click here to edit your password</a> <br /><br /> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000"> If the link above doesn\'t work, try copying the following url into your browser:<br />     <a href="#" style="color: #c06014; text-decoration: none;">{{confirmation_url}}</a> </font> <br /><br /> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000"> This email is sent to you because somebody (hopefully you) requested a password change for your account. If you did not request a new password, please forgive us and ignore this message. </font>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 7;
        $template->template_type    = \app\components\mail\template\TemplateTypeCustomer::TEMPLATE_TYPE;
        $template->name             = 'Registration Confirmation';
        $template->subject          = 'Registration Confirmation';
        $template->content          = '<h1>Registration Confirmation</h1> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">Hello there, {{customer_first_name}}    <br /><br /> It\'s a pleasure to meet you!     You\'ve signed up to be a registered user. </font> <br /><br /> <a href="{{login_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Log in to manage your account.</a> <br /><br /> <br /><br /> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000"> This email was sent to {{customer_email}}. </font>';
        $template->save();

        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 8;
        $template->template_type    = \app\components\mail\template\TemplateTypeAdmin::TEMPLATE_TYPE;
        $template->name             = 'Admin Registration Confirmation';
        $template->subject          = 'Admin Registration Confirmation';
        $template->content          = '<h1>Registration Confirmation</h1> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">Hello there, {{admin_first_name}}     <br /><br /> It\'s a pleasure to meet you!     You\'ve signed up to be an admin registered user. </font> <br /><br /> <a href="{{reset_password_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Reset Password to login and manage your account.</a> <br /><br /> <br /><br /> <font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000"> This email was sent to {{admin_email}}. </font>';
        $template->save();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%mail_template}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
