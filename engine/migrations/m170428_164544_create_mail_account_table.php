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
 * Handles the creation of table `mail_account`.
 */
class m170428_164544_create_mail_account_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%mail_account}}', [
            'account_id'        => $this->primaryKey(),
            'account_name'      => $this->string()->notNull(),
            'hostname'          => $this->string()->notNull(),
            'username'          => $this->string()->notNull()->unique(),
            'password'          => $this->string()->notNull(),
            'port'              => $this->integer()->unsigned()->notNull(),
            'encryption'        => $this->string(),
            'timeout'           => $this->integer()->unsigned(),
            'from'              => $this->string()->notNull(),
            'reply_to'          => $this->string(),
            'used_for'          => $this->string()->notNull(),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%mail_account}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
