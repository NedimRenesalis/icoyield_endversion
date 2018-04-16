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

use app\helpers\LocationHelper;
use app\models\options\Contact;
use app\models\Page;
use yii\web\Response;


/**
 * Controls the actions for settings section
 *
 * @Class ContactController
 * @package app\modules\admin\controllers
 */
class ContactController extends \app\modules\admin\yii\web\Controller
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
        $model = new Contact();
        $page = new Page();

        if ($model->load(request()->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $model,
                'pageModel' => $page
            ]);
        }
    }

    /**
     * @return \yii\web\Response
     */

    public function actionVerifyAddress()
    {
        /* allow only ajax calls*/
        if (!request()->isAjax) {
            return $this->redirect(['admin/index']);
        }

        response()->format = Response::FORMAT_JSON;

        $countryAddress = request()->post('country');
        $zoneAddress = request()->post('zone');
        $cityAddress = request()->post('city');
        $zipAddress = request()->post('zip');



        $coordinates = LocationHelper::getCoordinates($countryAddress, $zoneAddress, $cityAddress, $zipAddress);

        return ['result'=>'success', 'content' => $coordinates];

    }


}
