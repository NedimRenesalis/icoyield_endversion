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
 * Handles the creation of table `order_transaction`.
 */
class m170323_113608_create_order_transaction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%order_transaction}}', [
            'transaction_id'                => $this->primaryKey(),
            'order_id'                      => $this->integer()->defaultValue(null),
            'gateway'                       => $this->string()->defaultValue(null),
            'type'                          => $this->string(20)->notNull()->defaultValue('capture'),
            'transaction_reference'         => $this->string(100)->defaultValue(null),
            'gateway_response'              => $this->text()->defaultValue(null),
            'created_at'                    => $this->dateTime()->notNull(),
            'updated_at'                    => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('order_transaction_order_id_idx', '{{%order_transaction}}', 'order_id');
        $this->addForeignKey('order_transaction_order_id_fk', '{{%order_transaction}}', 'order_id', '{{%order}}', 'order_id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%order_transaction}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
