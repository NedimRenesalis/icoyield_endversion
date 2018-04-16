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
use \app\models\Group;
use \app\models\GroupRouteAccess;

/**
 * Handles the creation for table `group`.
 */
class m161008_205543_create_group_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%group}}', [
            'group_id'              => $this->primaryKey(),
            'name'                  => $this->string(100),
            'status'                => $this->char(15)->notNull()->defaultValue('inactive'),
            'created_at'            => $this->dateTime()->notNull(),
            'updated_at'            => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createIndex('group_group_id_idx', '{{%group}}', ['group_id']);

        $this->createTable('{{%group_route_access}}', [
            'route_id'              => $this->primaryKey(),
            'group_id'              => $this->integer()->notNull(),
            'route'                 => $this->string(100),
            'access'                => $this->char(5)->notNull()->defaultValue('allow'),
            'created_at'            => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('group_route_access_group_id_fk1', '{{%group_route_access}}', 'group_id', '{{%group}}', 'group_id', 'CASCADE', 'NO ACTION');


        // create default group.
        $group = new Group();
        $group->group_id  = 1;
        $group->name      = 'Admin';
        $group->status     = 'active';
        $group->save();

        // create default allow permission to the default group
        $routesAccess = $group->getAllRoutesAccess();
        foreach ($routesAccess as $data){
            foreach ($data['routes'] as $route){
                $groupRouteAccess = new GroupRouteAccess();
                $groupRouteAccess->group_id = 1;
                $groupRouteAccess->route = $route->route;
                $groupRouteAccess->access = GroupRouteAccess::ALLOW;
                $groupRouteAccess->save();
            }
        }

        // if group is deleted user group_id is null
        $this->addForeignKey('group_route_access_group_id_fk2', '{{%user}}', 'group_id', '{{%group}}', 'group_id', 'SET NULL', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%group}}');
        $this->dropTable('{{%group_route_access}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
