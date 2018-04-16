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

namespace app\models;

use \app\helpers\FileSystemHelper;

/**
 * Class GroupRouteAccess
 * @package app\models
 */
class GroupRouteAccess extends \app\models\auto\GroupRouteAccess
{
    const ALLOW = 'allow';

    const DENY = 'deny';

    public $name;

    public $description;

    public $controller;

    public $action;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'route'], 'required'],
            [['route'], 'string', 'max' => 255],
            [['access'], 'string', 'max' => 5],
            ['access', 'in', 'range' => array_keys($this->getAccessOptions())],
        ];
    }

    /**
     * @return array
     */
    public function getAccessOptions()
    {
        return array(
            self::ALLOW => t('app', ucfirst(self::ALLOW)),
            self::DENY  => t('app', ucfirst(self::DENY)),
        );
    }

    /**
     * @param $groupId
     * @return array
     */
    public static function findAllByGroupId($groupId)
    {
        $items = self::getRoutesFromFiles();
        $routes = array();
        foreach ($items as $index => $item) {
            $routes[$index] = array('controller' => $item['controller'], 'routes' => array());
            foreach ($item['routes'] as $action) {
                $model = self::findOne(array(
                    'group_id' => $groupId,
                    'route'    => $action['route'],
                ));
                if (empty($model)) {
                    $model = new self();
                    $model->group_id = $groupId;
                    $model->route    = $action['route'];
                    $model->access   = self::ALLOW;
                }
                $model->name        = t('app', $action['name']);
                $model->description = t('app', $action['description']);
                $routes[$index]['routes'][] = $model;
            }
        }
        return $routes;
    }

    /**
     * @return array
     */
    protected static function getRoutesFromFiles()
    {
        $modules = [
            [
                'id'      => 'admin',
                'exclude' => [
                    \Yii::getAlias('@app/modules/admin/controllers/DashboardController.php'),
                    \Yii::getAlias('@app/modules/admin/controllers/AdminController.php')
                ],
                'controllerPath' => \Yii::getAlias('@app/modules/admin/controllers'),
                'controllerNamespace' => '\\app\\modules\\admin\\controllers\\',
            ]

        ];


        $info = [];
        foreach ($modules as $module){
            $files = FileSystemHelper::readDirectoryContents($module['controllerPath'], true);
            $files = array_diff($files, $module['exclude']);
            sort($files);

            foreach ($files as $file) {
                $fileNameNoExt = basename($file, '.php');
                $controllerId  = \yii\helpers\Inflector::camel2id(substr($fileNameNoExt, 0, -10));
                $fileNameNoExt = $module['controllerNamespace'] . basename($file, '.php');
                $moduleId      = $module['id'];

                if (!class_exists($fileNameNoExt, false)) {
                    require_once $file;
                }

                $refl    = new \ReflectionClass(new $fileNameNoExt($controllerId,$moduleId));
                $methods = $refl->getMethods(\ReflectionMethod::IS_PUBLIC);
                $routes  = array();

                foreach ($methods as $method) {

                    if (strpos($method->name, 'action') !== 0 || strpos($method->name, 'actions') === 0) {
                        continue;
                    }
                    $actionId =  \yii\helpers\Inflector::camel2id(substr($method->name, 6));
                    $routes[] = array_merge(array('route' => $module['id'] . '/' . $controllerId . '/' . $actionId), self::extractObjectInfo($method));
                }
                $data = array(
                    'controller' => self::extractObjectInfo($refl),
                    'routes'     => $routes,
                );
                $info[] = $data;
            }
        }
        return $info;
    }

    /**
     * @param $reflObj
     * @return array
     */
    protected static function extractObjectInfo($reflObj)
    {
        $comment = $reflObj->getDocComment();
        $info    = [
            'name' => '',
            'description' => '',
        ];

        if ($reflObj instanceof \ReflectionMethod) {
            $info['name'] = ucfirst(str_replace('_', ' ', substr(\yii\helpers\Inflector::camel2words($reflObj->name), 6)));
        } elseif ($reflObj instanceof \ReflectionClass) {
            $nameArray=explode('\\',$reflObj->name);
            $name=$nameArray[key( array_slice( $nameArray, -1, 1, TRUE ) )];
            $info['name'] = ucfirst(str_replace('_', ' ', substr(\yii\helpers\Inflector::camel2words($name), 0, -11)));
        }

        $info['name'] = t('app', $info['name']);

        if (empty($info['description'])) {
            $comment = preg_replace('#@(.*?)\n#s', '', $comment);
            $comment = str_replace(array('*', '/', $reflObj->name), '', $comment);
            $comment = trim($comment);
            $comment = str_replace(array("\n", "\t"), "", $comment);
            $comment = preg_replace('/\s{2,}/', ' ', $comment);
            $info['description'] = trim($comment);
        }
        $info['description'] = ucfirst($info['description']);
        $info['description'] = t('app', $info['description']);
        return $info;
    }



}