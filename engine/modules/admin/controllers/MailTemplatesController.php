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

use app\components\mail\template\TemplateType;
use app\models\MailTemplate;
use app\models\MailTemplateSearch;
use app\modules\admin\yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Controls the actions for mail templates section
 *
 * @Class MailTemplatesController
 * @package app\modules\admin\controllers
 */
class MailTemplatesController extends Controller
{
    /**
     * Lists all the templates
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MailTemplateSearch();
        $dataProvider = $searchModel->search(request()->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates a specific template
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $templateType = TemplateType::create($model->template_type);
        $vars = $templateType->getVarsList();

        if ($model->load(request()->post()) && $model->save()) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'vars'  => $vars,
            ]);
        }
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = MailTemplate::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
