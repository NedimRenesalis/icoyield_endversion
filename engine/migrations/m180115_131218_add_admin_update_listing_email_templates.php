<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

use yii\db\Migration;

/**
 * Class m180115_131218_add_admin_update_listing_email_templates
 */
class m180115_131218_add_admin_update_listing_email_templates extends Migration
{
    public function up()
    {
        $template                   = new \app\models\MailTemplate();
        $template->template_id      = 13;
        $template->name             = 'Ad was updated by admin';
        $template->template_type    = \app\components\mail\template\TemplateTypeAd::TEMPLATE_TYPE;
        $template->subject          = 'The admin has updated your ad';
        $template->content          = '<h1>Your Ad was updated.</h1><font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">Hello there, {{customer_name}}<br /><br />Your ad <strong>"{{ad_title}}"</strong> from {{general_site_name}} was updated by the admin.<br /><br />If you want to see the updated version of the ad, please click on the button below.</font><br /><br /><br /><a href="{{ad_url}}" style="display: inline-block; padding: 5px 25px; background-color: #c06014; color: #ffffff; font-family: \'Oswald\', sans-serif; text-decoration: none">Preview your ad now</a><br /><br /><br /><br /><font face="Open Sans, sans-serif" style="font-size:14px;" color="#000000">This email was sent to {{customer_email}}.</font>';
        $template->save();
    }

    public function down()
    {
        return \app\models\MailTemplate::deleteAll(['template_id' => [13]]);
    }

}