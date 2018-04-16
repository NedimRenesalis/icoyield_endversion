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
use \app\models\User;

/**
 * Handles the creation for table `user`.
 */
class m160908_093937_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%user}}', [
            'user_id'               => $this->primaryKey(),
            'user_uid'              => $this->char(13),
            'group_id'              => $this->integer()->defaultValue(null),
            'first_name'            => $this->string(100),
            'last_name'             => $this->string(100),
            'email'                 => $this->string(150),
            'password'              => $this->string(64),
            'auth_key'              => $this->string(64),
            'access_token'          => $this->string(64),
            'password_reset_key'    => $this->string(32)->defaultValue(null),
            'timezone'              => $this->string(50),
            'avatar'                => $this->string(255)->Null(),
            'status'                => $this->char(15)->notNull()->defaultValue('inactive'),
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

        $this->dropTable('{{%user}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
