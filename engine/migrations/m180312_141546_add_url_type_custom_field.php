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
 * Class m180307_120707_add_the_expired_notification_email_templates
 */
class m180312_141546_add_url_type_custom_field extends Migration
{
    public function up()
    {
        // create url field;
        $field = new app\models\CategoryFieldType();
        $field->type_id = 5;
        $field->name = 'url';
        $field->class_name = 'app\fieldbuilder\url\FieldBuilderTypeUrl';
        $field->description = 'This is a default url field';
        $field->save();
    }

    public function down()
    {
        return \app\models\CategoryFieldType::deleteAll(['type_id' => [5]]);
    }
}
