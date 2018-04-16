<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

use yii\db\Migration;

/**
 * Class m180105_141431_alter_city_location_column
 */
class m180105_141431_alter_city_location_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createIndex(
            'idx-location-city',
            '{{%location}}',
            'city'
        );
    }
    /**
     * @inheritdoc
     */
    public function down()
    {

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropIndex(
            'idx-location-city',
            '{{%location}}'
        );

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
