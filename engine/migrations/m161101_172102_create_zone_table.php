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
 * Handles the creation for table `zone`.
 */
class m161101_172102_create_zone_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%zone}}', [
            'zone_id'           => $this->primaryKey(),
            'country_id'        => $this->integer(11)->notNull(),
            'name'              => $this->string(150)->notNull(),
            'code'              => $this->string(50)->notNull(),
            'status'            => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('zone_country_id_idx', '{{%zone}}', ['country_id']);

        $this->addForeignKey('zone_country_id_fk', '{{%zone}}', 'country_id', '{{%country}}', 'country_id', 'CASCADE', 'NO ACTION');


        // add country and zone data from SQL file
        $prefix = db()->tablePrefix;
        $query = \app\helpers\CommonHelper::getQueriesFromSqlFile(realpath(Yii::$app->basePath) . '/data/sql/country-zone.sql', $prefix);
        foreach ($query as $q){
            db()->createCommand($q)->execute();
        }


    }





    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%zone}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
