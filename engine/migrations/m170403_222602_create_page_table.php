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
 * Handles the creation of table `page`.
 */
class m170403_222602_create_page_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%page}}', [
            'page_id'           => $this->primaryKey(),
            'title'             => $this->string(80)->notNull(),
            'slug'              => $this->string(110)->notNull()->unique(),
            'keywords'          => $this->string(100),
            'description'       => $this->string(160),
            'content'           => $this->text()->notNull(),
            'section'           => $this->smallInteger(5)->unsigned(),
            'sort_order'        => $this->smallInteger(5),
            'status'            => $this->char(20)->notNull()->defaultValue('active'),
            'created_at'        => $this->dateTime()->notNull(),
            'updated_at'        => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('section_sort_order_unique_idx', '{{%page}}', ['section', 'sort_order'], true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%page}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
