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
 * Handles the creation of table `order_tax`.
 */
class m170525_012351_create_order_tax_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%order_tax}}', [
            'order_tax_id'              => $this->primaryKey(),
            'order_id'                  => $this->integer()->notNull(),
            'tax_name'                  => $this->string()->notNull(),
            'tax_price'                 => $this->float()->notNull(),
            'tax_percent'               => $this->decimal(4,2)->notNull(),
            'created_at'                => $this->dateTime()->notNull(),
            'updated_at'                => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('order_tax_order_id_fk', '{{%order_tax}}', 'order_id', '{{%order}}', 'order_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%order_tax}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
