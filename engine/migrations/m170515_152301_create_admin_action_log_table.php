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
 * Handles the creation of table `admin_action_log`.
 */
class m170515_152301_create_admin_action_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%admin_action_log}}', [
            'action_id'                 => $this->primaryKey(),
            'controller_name'           => $this->string()->notNull(),
            'action_name'               => $this->string()->notNull(),
            'changed_model'             => $this->string()->notNull(),
            'changed_data'              => 'LONGTEXT NOT NULL',
            'element'                   => $this->string()->notNull(),
            'changed_by'                => $this->integer(),
            'created_at'                => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('admin_action_log_changed_by_idx', '{{%admin_action_log}}', 'changed_by');
        $this->addForeignKey('admin_action_log_changed_by_fk', '{{%admin_action_log}}', 'changed_by', '{{%user}}', 'user_id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%admin_action_log}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
