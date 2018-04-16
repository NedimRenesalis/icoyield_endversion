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
 * Handles the creation for table `category_field_option`.
 */
class m161102_111931_create_category_field_option_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%category_field_option}}', [
            'option_id'       => $this->primaryKey(),
            'field_id'        => $this->integer()->notNull(),
            'name'            => $this->string(150)->notNull(),
            'value'           => $this->string(255),
            'created_at'      => $this->dateTime()->notNull(),
            'updated_at'      => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('category_field_option_field_id_fk', '{{%category_field_option}}', 'field_id', '{{%category_field}}', 'field_id', 'CASCADE', 'NO ACTION');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%category_field_option}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
