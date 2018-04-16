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
 * Handles the creation of table `customer`.
 */
class m170213_224059_create_customer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%customer}}', [
            'customer_id'           => $this->primaryKey(),
            'customer_uid'          => $this->char(13),
            'group_id'              => $this->integer()->defaultValue(1),
            'first_name'            => $this->string(100),
            'last_name'             => $this->string(100),
            'email'                 => $this->string(150),
            'password_hash'         => $this->string(64),
            'auth_key'              => $this->string(64),
            'access_token'          => $this->string(64),
            'password_reset_key'    => $this->string(32)->defaultValue(null),
            'phone'                 => $this->string(50),
            'gender'                => $this->char(1),
            'birthday'              => $this->dateTime(),
            'avatar'                => $this->string(255)->defaultValue(''),
            'source'                => $this->string(50)->defaultValue('Website'),
            'status'                => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'            => $this->dateTime()->notNull(),
            'updated_at'            => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%customer}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
