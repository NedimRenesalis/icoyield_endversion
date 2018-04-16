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
 * Handles the creation for table `category`.
 */
class m161001_180319_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%category}}', [
            'category_id'   => $this->primaryKey(),
            'parent_id'     => $this->integer()->null()->defaultValue(null),
            'name'          => $this->string(100)->notNull(),
            'slug'          => $this->string(110)->notNull()->unique(),
            'icon'          => $this->string(20)->defaultValue('fa-circle'),
            'description'   => $this->text()->defaultValue(null),
            'sort_order'    => $this->integer()->notNull(),
            'status'        => $this->char(15)->notNull()->defaultValue('active'),
            'created_at'    => $this->dateTime()->notNull(),
            'updated_at'    => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('category_parent_id_idx', '{{%category}}', ['parent_id']);
        $this->addForeignKey('category_parent_id_fk', '{{%category}}', 'parent_id', '{{%category}}', 'category_id', 'CASCADE', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%category}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
