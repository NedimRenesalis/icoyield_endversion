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
 * Handles the creation of table `listing_favorite`.
 */
class m170430_235753_create_listing_favorite_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%listing_favorite}}', [
            'favorite_id'                       => $this->primaryKey(),
            'customer_id'                       => $this->integer()->notNull(),
            'listing_id'                        => $this->integer()->notNull(),
            'created_at'                        => $this->dateTime()->notNull(),
            'updated_at'                        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('listing_favorite_customer_id_fk', '{{%listing_favorite}}', 'customer_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('listing_favorite_listing_id_fk', '{{%listing_favorite}}', 'listing_id', '{{%listing}}', 'listing_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%listing_favorite}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
