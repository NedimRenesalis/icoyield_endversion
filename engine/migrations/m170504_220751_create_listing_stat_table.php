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
 * Handles the creation of table `listing_stat`.
 */
class m170504_220751_create_listing_stat_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%listing_stat}}', [
            'listing_id'                        => $this->primaryKey(),
            'total_views'                       => $this->integer()->defaultValue(0),
            'facebook_shares'                   => $this->integer()->defaultValue(0),
            'twitter_shares'                    => $this->integer()->defaultValue(0),
            'mail_shares'                       => $this->integer()->defaultValue(0),
            'favorite'                          => $this->integer()->defaultValue(0),
            'show_phone'                        => $this->integer()->defaultValue(0),
            'show_mail'                         => $this->integer()->defaultValue(0),
            'created_at'                        => $this->dateTime()->notNull(),
            'updated_at'                        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('listing_stat_listing_id_idx', '{{%listing_stat}}', 'listing_id');
        $this->addForeignKey('listing_stat_listing_id_fk', '{{%listing_stat}}', 'listing_id', '{{%listing}}', 'listing_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%listing_stat}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
