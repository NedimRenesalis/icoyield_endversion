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
 * Handles the creation of table `customer_store`.
 */
class m170708_210603_create_customer_store_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%customer_store}}', [
            'store_id'              => $this->primaryKey(),
            'slug'                  => $this->string(110)->notNull()->unique(),
            'customer_id'           => $this->integer()->notNull(),
            'store_name'            => $this->string(30)->notNull()->unique(),
            'company_name'          => $this->string(30)->notNull()->unique(),
            'company_no'            => $this->string(20),
            'vat'                   => $this->string(20),
            'status'                => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'            => $this->dateTime()->notNull(),
            'updated_at'            => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('customer_store_store_id_idx', '{{%customer_store}}', 'store_id');
        $this->addForeignKey('customer_store_customer_id_fk', '{{%customer_store}}', 'customer_id', '{{%customer}}', 'customer_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%customer_store}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
