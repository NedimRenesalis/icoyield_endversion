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
 * Handles the creation of table `tax`.
 */
class m170513_232559_create_tax_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%tax}}', [
            'tax_id'                            => $this->primaryKey(),
            'country_id'                        => $this->integer()->defaultValue(null),
            'zone_id'                           => $this->integer()->defaultValue(null),
            'name'                              => $this->string()->notNull(),
            'percent'                           => $this->decimal(4,2)->notNull(),
            'is_global'                         => $this->char(3)->defaultValue('no'),
            'status'                            => $this->char(15)->defaultValue('active'),
            'created_at'                        => $this->dateTime()->notNull(),
            'updated_at'                        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('tax_country_id_idx', '{{%tax}}', 'country_id');
        $this->createIndex('tax_zone_id_idx', '{{%tax}}', 'zone_id');

        $this->addForeignKey('tax_country_id_fk', '{{%tax}}', 'country_id', '{{%country}}', 'country_id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('tax_zone_id_fk', '{{%tax}}', 'zone_id', '{{%zone}}', 'zone_id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%tax}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
