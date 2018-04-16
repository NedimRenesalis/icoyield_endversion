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

class m170711_140626_alter_notnull_listing_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->update('{{%listing}}', ['promo_expire_at' => new \yii\db\Expression('created_at')], ['promo_expire_at' => null]);

        $this->alterColumn('{{%listing}}', 'promo_expire_at', $this->dateTime()->notNull());
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->alterColumn('{{%listing}}', 'promo_expire_at', $this->dateTime());

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
