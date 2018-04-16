<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

namespace app\extensions\watermark\admin\controllers;

use app\extensions\watermark\models\options\Watermark;

class WatermarkController extends \app\modules\admin\yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * Controls general settings
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = new Watermark();

        if ($model->load(request()->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('@app/extensions/watermark/admin/views/index.php', [
                'model' => $model,
            ]);
        }

    }
}
