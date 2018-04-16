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

namespace app\modules\admin\components;

use app\models\AdminActionLog;
use yii\base\Component;
use yii\db\BaseActiveRecord;
use yii\web\User;
use yii\base\Model;
use yii\base\Event;

/**
 *
 * Configuration of component
 *
 * ~~~
 *    'components' => [
 *        ...
 *        'trackUserAction' => [
 *            'class' => 'app\modules\admin\components\TrackUserActionComponent',
 *        ],
 *        ...
 *    ],
 * ~~~
 * Usage
 *      app()->getModule('admin')->trackUserAction->track($controller, $action, User $user, AfterSaveEvent $event);
 *
 *      $controller name of controller to track
 *      $action name of action to track
 *      $user user that made action
 *      $event object that fired event
 *
 * @package app\modules\admin\components
 */
class TrackUserActionComponent extends Component
{
    /**
     * @var array List of attributes that should be excluded from tracking
     */
    public $excludeModelAttributes = ['created_at', 'updated_at'];

    public $eventSenderDescriptorKeys = ['name', 'title', 'slug', 'email'];

    /**
     * Track changes
     *
     * @param       $controller
     * @param       $action
     * @param User  $user
     * @param Event $event
     * @return bool
     */
    public function track($controller, $action, User $user, Event $event)
    {
        $eventSender = $event->sender;

        if ($event->name != 'beforeDelete') {
            if (empty($event->changedAttributes)) {
                return false;
            }

            $trackChangedAttributes = array_diff_key($event->changedAttributes, array_flip($this->excludeModelAttributes));
            $trackChangedAttributes = $this->excludeNonStringDirtyAttributes($trackChangedAttributes, $eventSender);
        } else {
            $trackChangedAttributes = array_diff_key($eventSender->getAttributes(), array_flip($this->excludeModelAttributes));
        }

        if (empty($trackChangedAttributes)) {
            return false;
        }

        $changedData = [];
        foreach ($trackChangedAttributes as $propertyName => $oldValue) {
            // get updated value
            $changedData[$propertyName] = $eventSender->$propertyName;
        }

        $logItem = new AdminActionLog();
        $logItem->controller_name = $controller;
        $logItem->action_name = $action;
        $logItem->changed_by = $user->id;
        $logItem->changed_model = $eventSender::className();
        $logItem->element = $this->generateItemDescriptor($eventSender);
        $logItem->changed_data = json_encode($changedData);

        return $logItem->save(false);
    }

    /**
     * Exclude non string dirty attributes because of
     * "All non-string attributes are dirty when submitting a form using MySQL native driver",
     * expected behaviour of Yii2
     * https://github.com/yiisoft/yii2/issues/2790
     *
     * @param $changedAttributes
     * @param $eventSender
     * @return array
     */
    protected function excludeNonStringDirtyAttributes($changedAttributes, $eventSender)
    {
        $attributes = [];
        foreach ($changedAttributes as $attrName => $attrValue) {
            if ($attrValue != $eventSender->$attrName) {
                $attributes[$attrName] = $attrValue;
            }
        }

        return $attributes;
    }

    /**
     * Generate log item descriptor by model
     *
     * @param Model $model
     * @return string
     */
    protected function generateItemDescriptor(Model $model)
    {
        $modelName = \yii\helpers\StringHelper::basename(get_class($model));
        $descriptor = '';
        $modelAttributes = $model->getAttributes();
        foreach ($this->eventSenderDescriptorKeys as $descriptorKey) {
            if (array_key_exists($descriptorKey, $modelAttributes)) {
                $descriptor = $modelAttributes[$descriptorKey];
                break;
            }
        }

        // get primary key if data is not enough
        if (!$descriptor && ($model instanceof BaseActiveRecord)) {
            $modelPk = $model->getPrimaryKey(true);
            $descriptor = implode(', ', array_map(
                function ($v, $k) {
                    return $k . ': ' . $v;
                },
                $modelPk,
                array_keys($modelPk)
            ));
        }

        return $descriptor ? ($modelName . ' > ' . $descriptor) : $modelName;
    }
}