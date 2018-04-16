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
 * Handles the creation of table `listing_package`.
 */
class m170223_225821_create_listing_package_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%listing_package}}', [
            'package_id'                => $this->primaryKey(),
            'title'                     => $this->string(100),
            'price'                     => $this->integer(),
            'listing_days'              => $this->integer(),
            'promo_days'                => $this->integer(),
            'promo_show_featured_area'  => $this->char(3)->notNull()->defaultValue('no'),
            'promo_show_at_top'         => $this->char(3)->notNull()->defaultValue('no'),
            'promo_sign'                => $this->char(3)->notNull()->defaultValue('no'),
            'recommended_sign'          => $this->char(3)->notNull()->defaultValue('no'),
            'auto_renewal'              => $this->integer(),
            'created_at'                => $this->dateTime()->notNull(),
            'updated_at'                => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%listing_package}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
