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
 * Handles the creation of table `listing`.
 */
class m170223_225841_create_listing_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%listing}}', [
            'listing_id'                    => $this->primaryKey(),
            'package_id'                    => $this->integer()->defaultValue(null),
            'customer_id'                   => $this->integer()->notNull(),
            'location_id'                   => $this->integer()->notNull(),
            'category_id'                   => $this->integer()->notNull(),
            'currency_id'                   => $this->integer()->notNull(),
            'title'                         => $this->string(100)->notNull(),
            'slug'                          => $this->string(110)->notNull()->unique(),
            'description'                   => $this->text()->notNull(),
            'price'                         => $this->integer()->notNull(),
            'negotiable'                    => $this->char(3)->notNull()->defaultValue(0),
            'hide_phone'                    => $this->char(3)->notNull()->defaultValue(0),
            'hide_email'                    => $this->char(3)->notNull()->defaultValue(0),
            'remaining_auto_renewal'        => $this->integer()->notNull()->defaultValue(0),
            'promo_expire_at'               => $this->dateTime(),
            'listing_expire_at'             => $this->dateTime(),
            'status'                        => $this->char(20)->notNull()->defaultValue('active'),
            'created_at'                    => $this->dateTime()->notNull(),
            'updated_at'                    => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('listing_package_id_fk', '{{%listing}}', 'package_id', '{{%listing_package}}', 'package_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('listing_customer_id_fk', '{{%listing}}', 'customer_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('listing_location_id_fk', '{{%listing}}', 'location_id', '{{%location}}', 'location_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('listing_category_id_fk', '{{%listing}}', 'category_id', '{{%category}}', 'category_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('listing_currency_id_fk', '{{%listing}}', 'currency_id', '{{%currency}}', 'currency_id', 'CASCADE', 'NO ACTION');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%listing}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
