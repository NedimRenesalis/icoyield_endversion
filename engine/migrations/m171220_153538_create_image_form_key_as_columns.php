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
 * Class m171220_153538_add_image_form_key_column_to_listing_image
 */
class m171220_153538_create_image_form_key_as_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%listing_image}}', 'image_form_key', $this->string(8)->after('listing_id'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%listing_image}}', 'image_form_key');
    }
}
