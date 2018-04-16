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
 * Handles the creation of table `conversation`.
 */
class m170904_182951_create_conversation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%conversation}}', [
            'conversation_id'  => $this->primaryKey(),
            'conversation_uid' => $this->char(13),
            'seller_id'        => $this->integer()->notNull(),
            'buyer_id'         => $this->integer()->notNull(),
            'listing_id'       => $this->integer()->notNull(),
            'is_buyer_blocked' => $this->boolean()->unsigned()->defaultValue(0),
            'status'           => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'       => $this->dateTime()->notNull(),
            'updated_at'       => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('conversation_seller_customer_fk', '{{%conversation}}', 'seller_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('conversation_buyer_customer_fk', '{{%conversation}}', 'buyer_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('conversation_listing_fk', '{{%conversation}}', 'listing_id', '{{%listing}}', 'listing_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%conversation}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
