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

namespace app\modules\admin\yii\base;


use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use app\models\options\Base;

/**
 * Class AdminBootstrapInterface
 * @package app\modules\admin\yii\base
 */
class AdminBootstrapInterface implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * Loads all the settings into the app()->params array
     * @param Application $app the application currently running
     */

    public function bootstrap($app) {

        $app->getUrlManager()->addRules([
            'admin/index'              => 'admin/admin/index',
            'admin/logout'             => 'admin/admin/logout',
            'admin/forgot'             => 'admin/admin/forgot',
            'admin/reset_password'     => 'admin/admin/reset_password',
        ], false);

        // class-level event handlers of ActiveRecord, EVENT_AFTER_UPDATE
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_UPDATE, function ($event) {
            $moduleName = app()->controller->module->id;

            if ('admin' == $moduleName && $event->sender !== null) {
                app()->getModule('admin')->trackUserAction->track(app()->controller->id, app()->controller->action->id, app()->user, $event);
            }
        });

        // class-level event handlers of ActiveRecord, EVENT_AFTER_INSERT
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, function ($event) {
            $moduleName = app()->controller->module->id;
            $eventSender = $event->sender;

            if ('admin' == $moduleName && $eventSender !== null && $eventSender::className() !== 'app\models\AdminActionLog') {
                app()->getModule('admin')->trackUserAction->track(app()->controller->id, app()->controller->action->id, app()->user, $event);
            }
        });

        // class-level event handlers of ActiveRecord, EVENT_BEFORE_DELETE
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_BEFORE_DELETE, function ($event) {
            $moduleName = app()->controller->module->id;
            $eventSender = $event->sender;

            if ('admin' == $moduleName && $eventSender !== null) {
                app()->getModule('admin')->trackUserAction->track(app()->controller->id, app()->controller->action->id, app()->user, $event);
            }
        });

        // class-level event handlers of Base Model of Settings, EVENT_AFTER_UPDATE
        Event::on(Base::className(), Base::EVENT_AFTER_UPDATE, function ($event) {
            $moduleName = app()->controller->module->id;

            if ('admin' == $moduleName && $event->sender !== null) {
                app()->getModule('admin')->trackUserAction->track(app()->controller->id, app()->controller->action->id, app()->user, $event);
            }
        });

    }

}