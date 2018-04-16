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

class m170708_225857_create_order_store_as_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%order}}', 'company_name', $this->string(30)->defaultValue(null)->after('last_name'));
        $this->addColumn('{{%order}}', 'company_no', $this->string(20)->defaultValue(null)->after('company_name'));
        $this->addColumn('{{%order}}', 'vat', $this->string(20)->defaultValue(null)->after('company_no'));
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropColumn('{{%order}}', 'company_name');
        $this->dropColumn('{{%order}}', 'company_no');
        $this->dropColumn('{{%order}}', 'vat');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
