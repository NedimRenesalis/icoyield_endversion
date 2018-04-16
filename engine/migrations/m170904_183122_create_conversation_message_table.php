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
 * Handles the creation of table `conversation_message`.
 */
class m170904_183122_create_conversation_message_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%conversation_message}}', [
            'conversation_message_id' => $this->primaryKey(),
            'conversation_id'         => $this->integer()->notNull(),
            'seller_id'               => $this->integer(),
            'buyer_id'                => $this->integer(),
            'message'                 => $this->string(1000)->notNull(),
            'is_read'                 => $this->boolean()->unsigned()->defaultValue(0),
            'created_at'              => $this->dateTime()->notNull(),
            'updated_at'              => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('conversation_message_conversation_fk', '{{%conversation_message}}', 'conversation_id', '{{%conversation}}', 'conversation_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('conversation_message_seller_customer_fk', '{{%conversation_message}}', 'seller_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('conversation_message_buyer_customer_fk', '{{%conversation_message}}', 'buyer_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%conversation_message}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
