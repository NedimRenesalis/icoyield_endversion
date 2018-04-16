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

namespace app\modules\admin\controllers;


use app\helpers\CommonHelper;
use app\helpers\FileSystemHelper;
use yii\filters\VerbFilter;

/**
 * Controls the actions for gateways section
 *
 * @Class GatewaysController
 * @package app\modules\admin\controllers
 */
class GatewaysController extends \app\modules\admin\yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all the gateways and modify their settings
     *
     * @return string
     */
    public function actionIndex()
    {
        // extensions parser
        $extensions = FileSystemHelper::getDirectoryNames(\Yii::getAlias('@app/extensions'));
        foreach ($extensions as $gateway){
            $className = 'app\extensions\\' . $gateway . '\\' . ucfirst(strtolower($gateway));
            if (class_exists($className)) {
                $instance = new $className();
                if (options()->get('app.extensions.' . $gateway . '.status', 'disabled') === 'enabled') {
                    if ($instance->type == 'gateway') {
                        $auxModel = 'app\extensions\\' . strtolower($gateway) . '\models\\' . ucfirst(strtolower($gateway));
                        $model = new $auxModel();

                        //admin tab
                        app()->on('admin.gateways.tab', function ($event) use ($gateway, $model) {
                            echo app()->view->renderFile('@app/extensions/' . $gateway . '/views/gateway-admin-tab.php', [
                                'paymentMethod' => $gateway,
                            ]);
                        });

                        // admin form
                        app()->on('admin.gateways.form', function ($event) use ($gateway, $model) {
                            echo app()->view->renderFile('@app/extensions/' . $gateway . '/views/gateway-admin-form.php', [
                                'model' => $model,
                            ]);
                        });
                        if ($model->load(request()->post())) {
                            notify()->addSuccess(t('app', 'Your action is complete.'));
                            $model->save();
                        }
                    }
                }
            }
        }
        return $this->render('index');
    }
}
