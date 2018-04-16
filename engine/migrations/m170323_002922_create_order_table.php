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
 * Handles the creation of table `order`.
 */
class m170323_002922_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%order}}', [
            'order_id'              => $this->primaryKey(),
            'listing_id'            => $this->integer()->defaultValue(null),
            'customer_id'           => $this->integer()->notNull(),
            'promo_code_id'         => $this->integer()->defaultValue(null),
            'order_title'           => $this->string(100)->defaultValue(null),
            'first_name'            => $this->string(100)->notNull(),
            'last_name'             => $this->string(100)->notNull(),
            'country_id'            => $this->integer()->defaultValue(null),
            'zone_id'               => $this->integer()->defaultValue(null),
            'city'                  => $this->string(100)->notNull(),
            'zip'                   => $this->string(10)->notNull(),
            'phone'                 => $this->string(20)->defaultValue(null),
            'discount'              => $this->integer()->notNull()->defaultValue(0),
            'subtotal'              => $this->integer()->notNull()->defaultValue(0),
            'total'                 => $this->integer()->notNull()->defaultValue(0),
            'status'                => $this->string(10)->notNull()->defaultValue('unpaid'),
            'created_at'            => $this->dateTime()->notNull(),
            'updated_at'            => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('order_customer_id_idx', '{{%order}}', 'customer_id');
        $this->addForeignKey('order_customer_id_fk', '{{%order}}', 'customer_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');

        $this->createIndex('order_listing_id_idx', '{{%order}}', 'listing_id');
        $this->addForeignKey('order_listing_id_fk', '{{%order}}', 'listing_id', '{{%listing}}', 'listing_id', 'SET NULL', 'NO ACTION');

        $this->createIndex('order_country_id_idx', '{{%order}}', 'country_id');
        $this->addForeignKey('order_country_id_fk', '{{%order}}', 'country_id', '{{%country}}', 'country_id', 'SET NULL', 'NO ACTION');

        $this->createIndex('order_zone_id_idx', '{{%order}}', 'zone_id');
        $this->addForeignKey('order_zone_id_fk', '{{%order}}', 'zone_id', '{{%zone}}', 'zone_id', 'SET NULL', 'NO ACTION');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%order}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
