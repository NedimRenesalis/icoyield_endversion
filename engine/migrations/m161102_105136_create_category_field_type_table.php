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

class m161102_105136_create_category_field_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%category_field_type}}', [
            'type_id'       => $this->primaryKey(),
            'name'          => $this->string(50)->notNull(),
            'class_name'    => $this->string(255)->notNull(),
            'description'   => $this->string(255)->null(),
            'status'        => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'    => $this->dateTime()->notNull(),
            'updated_at'    => $this->dateTime()->notNull(),
        ], $tableOptions);

        // create text field;
        $field = new app\models\CategoryFieldType();
        $field->name = 'text';
        $field->class_name = 'app\fieldbuilder\text\FieldBuilderTypeText';
        $field->description = 'This is a default text field';
        $field->save();

        // create select field;
        $field = new app\models\CategoryFieldType();
        $field->name = 'select';
        $field->class_name = 'app\fieldbuilder\select\FieldBuilderTypeSelect';
        $field->description = 'This is a default select field';
        $field->save();

        // create select field;
        $field = new app\models\CategoryFieldType();
        $field->name = 'checkbox';
        $field->class_name = 'app\fieldbuilder\checkbox\FieldBuilderTypeCheckbox';
        $field->description = 'This is a default checkbox field';
        $field->save();

        // create text field;
        $field = new app\models\CategoryFieldType();
        $field->name = 'number';
        $field->class_name = 'app\fieldbuilder\number\FieldBuilderTypeNumber';
        $field->description = 'This is a default numeric field';
        $field->save();

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%category_field_type}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
