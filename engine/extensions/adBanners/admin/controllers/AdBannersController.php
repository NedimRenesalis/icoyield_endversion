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

namespace app\extensions\adBanners\admin\controllers;

use app\extensions\adBanners\helpers\AdBannersHelper;
use app\extensions\adBanners\models\options\AdBanners;


class AdBannersController extends \app\modules\admin\yii\web\Controller
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
        $model = new AdBanners();

        if ($model->load(request()->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('@app/extensions/adBanners/admin/views/index.php', [
                'model' => $model,
                'properties' => AdBannersHelper::getStaticAdBannersProperties()
            ]);
        }
    }
}
