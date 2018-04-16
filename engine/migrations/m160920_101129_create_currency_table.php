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
 * Handles the creation for table `currency`.
 */
class m160920_101129_create_currency_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%currency}}', [
            'currency_id'       => $this->primaryKey(),
            'name'              => $this->string(100),
            'code'              => $this->string(100),
            'symbol'            => $this->string(100),
            'status'            => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ], $tableOptions);

        // add currency data from SQL file
        $prefix = db()->tablePrefix;
        $query = \app\helpers\CommonHelper::getQueriesFromSqlFile(realpath(Yii::$app->basePath) . '/data/sql/currency.sql', $prefix);
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

        $this->dropTable('{{%currency}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}


