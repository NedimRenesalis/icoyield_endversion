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
 * Handles the creation of table `invoice`.
 */
class m170420_145138_create_invoice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%invoice}}', [
            'invoice_id'        => $this->primaryKey(),
            'order_id'          => $this->integer()->notNull(),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('invoice_order_id_idx', '{{%invoice}}', 'order_id');
        $this->addForeignKey('invoice_order_id_fk', '{{%invoice}}', 'order_id', '{{%order}}', 'order_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%invoice}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
