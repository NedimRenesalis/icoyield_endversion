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

class m161102_105921_create_category_field_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%category_field}}', [
            'field_id'       => $this->primaryKey(),
            'type_id'        => $this->integer()->notNull(),
            'category_id'    => $this->integer()->notNull(),
            'label'          => $this->string(255),
            'unit'           => $this->string(25)->defaultValue(null),
            'default_value'  => $this->string(255)->defaultValue(null),
            'help_text'      => $this->string(255),
            'required'       => $this->char(3)->notNull()->defaultValue('no'),
            'sort_order'     => $this->integer()->notNull()->defaultValue(0),
            'status'         => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'     => $this->dateTime()->notNull(),
            'updated_at'     => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('category_field_type_id_fk', '{{%category_field}}', 'type_id', '{{%category_field_type}}', 'type_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('category_field_category_id_fk', '{{%category_field}}', 'category_id', '{{%category}}', 'category_id', 'CASCADE', 'NO ACTION');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%category_field}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
