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
 * Handles the creation of table `category_field_value`.
 */
class m170313_183224_create_category_field_value_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%category_field_value}}', [
            'value_id'                          => $this->primaryKey(),
            'field_id'                          => $this->integer()->notNull(),
            'listing_id'                        => $this->integer()->notNull(),
            'value'                             => $this->string()->notNull(),
            'created_at'                        => $this->dateTime()->notNull(),
            'updated_at'                        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('listing_field_id_fk', '{{%category_field_value}}', 'field_id', '{{%category_field}}', 'field_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('listing_listing_id_fk', '{{%category_field_value}}', 'listing_id', '{{%listing}}', 'listing_id', 'CASCADE', 'NO ACTION');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%category_field_value}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
