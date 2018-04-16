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

namespace app\modules\admin;

use Yii;
use app\yii\base\Event;
use yii\web\ErrorHandler;


/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        app()->name='admin';

        // Custom config for admin module
        Yii::configure($this, require(__DIR__ .DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR .'main.php'));

        \Yii::configure($this, [
            'components' => [
                'errorHandler' => [
                    'class' => ErrorHandler::className(),
                    'errorAction' => 'admin/admin/error',
                ]
            ],
        ]);

        /** @var ErrorHandler $handler */
        $handler = $this->get('errorHandler');
        app()->set('errorHandler', $handler);
        $handler->register();

        app()->trigger('app.modules.admin.init', new Event(['params' => ['module' => $this]]));
    }


}
