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
 * Handles the creation for table `location`.
 */
class m161101_205450_create_location_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%location}}', [
            'location_id'       => $this->primaryKey(),
            'country_id'        => $this->integer()->notNull(),
            'zone_id'           => $this->integer()->notNull(),
            'city'              => $this->string(100)->null(),
            'zip'               => $this->char(10)->null(),
            'latitude'          => $this->decimal(10,6)->defaultValue(0)->null(),
            'longitude'         => $this->decimal(10,6)->defaultValue(0)->null(),
            'retries'           => $this->integer()->defaultValue(0),
            'status'            => $this->char(15)->defaultValue('active'),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('location_country_id_fk', '{{%location}}', 'country_id', '{{%country}}', 'country_id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('location_zone_id_fk', '{{%location}}', 'zone_id', '{{%zone}}', 'zone_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%location}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
