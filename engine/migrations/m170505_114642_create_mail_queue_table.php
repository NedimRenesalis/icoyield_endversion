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
 * Handles the creation of table `mail_queue`.
 */
class m170505_114642_create_mail_queue_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%mail_queue}}', [
            'id'                            => $this->primaryKey(),
            'subject'                       => $this->string(),
            'swift_message'                 => 'LONGTEXT NOT NULL',
            'attempts'                      => $this->smallInteger(5)->unsigned(),
            'message_template_type'         => $this->smallInteger(5)->unsigned()->notNull(),
            'last_attempt_time'             => $this->dateTime(),
            'time_to_send'                  => $this->dateTime(),
            'sent_time'                     => $this->dateTime(),
            'created_at'                    => $this->dateTime()->notNull(),

        ], $tableOptions);

        $this->createIndex('time_to_send_idx', '{{%mail_queue}}', 'time_to_send');
        $this->createIndex('sent_time_idx', '{{%mail_queue}}', ['sent_time']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%mail_queue}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
