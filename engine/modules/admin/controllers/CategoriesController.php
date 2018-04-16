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

use app\fieldbuilder\Type;
use app\models\CategoryField;
use app\models\CategoryFieldOption;
use app\models\CategoryFieldSearch;
use app\models\CategoryFieldType;
use app\models\Category;
use app\models\CategorySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\FrontendHelper;

/**
 * Controls the actions for categories section
 *
 * @Class CategoriesController
 * @package app\modules\admin\controllers
 */
class CategoriesController extends \app\modules\admin\yii\web\Controller
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
     * Lists all categories
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=20;

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a specific category
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $searchModel = new CategoryFieldSearch(['category_id'=>$id]);
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new category
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Category();

        $typeData           = [];
        $dbFieldsIds        = [];
        $dbOptionsIds       = [];
        $postFieldsIds      = [];
        $postOptionsIds     = [];

        $fontAwesomeIcons = FrontendHelper::getIcons();

        foreach (CategoryFieldType::find()->all() as $type) {
            $typeData[] = [
                'type'  => $type,
                'field' => null,
            ];
        }

        $fields = CategoryField::find()->joinWith('categoryFieldOptions')->where(['category_id' => $model->category_id])->orderBy(['sort_order' => SORT_ASC])->all();

        foreach ($fields as $field) {
            $typeData[] = [
                'type'  => $field->type,
                'field' => $field,
            ];

            if ($field->categoryFieldOptions) foreach ($field->categoryFieldOptions as $option) {
                $dbOptionsIds[] = (int)$option->option_id;
            }
            $dbFieldsIds[] = (int)$field->field_id;
        }

        foreach ($typeData as $data) {
            $type  = $data['type'];
            $field = $data['field'];
            if (!is_file(\Yii::getAlias('@'. str_replace('\\', '/', $type->class_name) . '.php'))) {
                continue;
            }
            $className = $type->class_name;
            $component = new $className();

            if (!($component instanceof Type)) {
                continue;
            }
            $component->field = $field;
            $component->handleAdminDisplay();
        }

        $saved = $model->load(request()->post()) && $model->save();

        if ($saved) {
            $categoryFields = (array)request()->post('CategoryField');
            $categoryFieldsOptions = (array)request()->post('CategoryFieldOption');

            foreach ($categoryFields as $fieldName=>$fields) {
                foreach ($fields as $fieldKey=>$fieldValue) {
                    $field = CategoryField::findOne((int)$fieldValue['field_id']);
                    $fieldType = CategoryFieldType::find()->where(['name'=>$fieldName])->one();

                    if (empty($field)) {
                        $field = new CategoryField();
                    }

                    $postFieldsIds[] = (int)$fieldValue['field_id'];

                    $field->attributes = $fieldValue;
                    $field->category_id = $model->category_id;
                    $field->type_id = $fieldType->type_id;
                    $field->save(false);

                    $fieldId = $field->field_id;

                    // Try to get Options if this field has one
                    if (!empty($categoryFieldsOptions) && isset($categoryFieldsOptions[$fieldName]) && isset($categoryFieldsOptions[$fieldName][$fieldKey])) {
                        foreach ($categoryFieldsOptions[$fieldName][$fieldKey] as $optionKey => $optionValue) {
                            $option = (!empty($optionValue['option_id'])) ? CategoryFieldOption::findOne((int)$optionValue['option_id']) : new CategoryFieldOption();
                            $option->attributes = $optionValue;
                            $option->field_id = (int)$fieldId;
                            $option->save(false);

                            $postOptionsIds[] = (int)$option->option_id;
                        }
                    }
                }
            }

            $fieldsIds = array_diff($dbFieldsIds, $postFieldsIds);
            CategoryField::deleteAll(['field_id'=>$fieldsIds]);

            $optionIds = array_diff($dbOptionsIds, $postOptionsIds);
            CategoryFieldOption::deleteAll(['option_id'=>$optionIds]);
        }

        if ($saved) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->category_id]);
        }

        return $this->render('form', [
            'action'=> 'create',
            'model' => $model,
            'fontAwesomeIcons' => $fontAwesomeIcons,
        ]);

    }

    /**
     * Updates a specific category
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $typeData           = [];
        $dbFieldsIds        = [];
        $dbOptionsIds       = [];
        $postFieldsIds      = [];
        $postOptionsIds     = [];

        $fontAwesomeIcons = FrontendHelper::getIcons();

        foreach (CategoryFieldType::find()->all() as $type) {
            $typeData[] = [
                'type'  => $type,
                'field' => null,
            ];
        }

        $fields = CategoryField::find()->joinWith('categoryFieldOptions')->where(['category_id' => $model->category_id])->orderBy(['sort_order' => SORT_ASC])->all();

        foreach ($fields as $field) {
            $typeData[] = [
                'type'  => $field->type,
                'field' => $field,
            ];

            if ($field->categoryFieldOptions) foreach ($field->categoryFieldOptions as $option) {
                $dbOptionsIds[] = (int)$option->option_id;
            }
            $dbFieldsIds[] = (int)$field->field_id;
        }

        foreach ($typeData as $data) {
            $type  = $data['type'];
            $field = $data['field'];
            if (!is_file(\Yii::getAlias('@'. str_replace('\\', '/', $type->class_name) . '.php'))) {
                continue;
            }
            $className = $type->class_name;
            $component = new $className();

            if (!($component instanceof Type)) {
                continue;
            }
            $component->field = $field;
            $component->handleAdminDisplay();
        }

        $saved = $model->load(request()->post()) && $model->save();

        if ($saved) {
            $categoryFields = (array)request()->post('CategoryField');
            $categoryFieldsOptions = (array)request()->post('CategoryFieldOption');

            foreach ($categoryFields as $fieldName=>$fields) {
                foreach ($fields as $fieldKey=>$fieldValue) {
                   $field = CategoryField::findOne((int)$fieldValue['field_id']);
                   $fieldType = CategoryFieldType::find()->where(['name'=>$fieldName])->one();

                   if (empty($field)) {
                       $field = new CategoryField();
                   }

                   $postFieldsIds[] = (int)$fieldValue['field_id'];

                   $field->attributes = $fieldValue;
                   $field->category_id = $model->category_id;
                   $field->type_id = $fieldType->type_id;
                   $field->save(false);

                   $fieldId = $field->field_id;

                   // Try to get Options if this field has one
                   if (!empty($categoryFieldsOptions) && isset($categoryFieldsOptions[$fieldName]) && isset($categoryFieldsOptions[$fieldName][$fieldKey])) {
                       foreach ($categoryFieldsOptions[$fieldName][$fieldKey] as $optionKey => $optionValue) {
                           $option = (!empty($optionValue['option_id'])) ? CategoryFieldOption::findOne((int)$optionValue['option_id']) : new CategoryFieldOption();
                           $option->attributes = $optionValue;
                           $option->field_id = (int)$fieldId;
                           $option->save(false);

                           $postOptionsIds[] = (int)$option->option_id;
                       }
                   }
                }
            }

            $fieldsIds = array_diff($dbFieldsIds, $postFieldsIds);
            CategoryField::deleteAll(['field_id'=>$fieldsIds]);

            $optionIds = array_diff($dbOptionsIds, $postOptionsIds);
            CategoryFieldOption::deleteAll(['option_id'=>$optionIds]);
        }

        if ($saved) {
            notify()->addSuccess(t('app','Your action is complete.'));
            return $this->redirect(['view', 'id' => $model->category_id]);
        }

        return $this->render('form', [
            'action'=> 'update',
            'model' => $model,
            'fontAwesomeIcons' => $fontAwesomeIcons,
        ]);
    }

    /**
     * Deletes a specific category
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/categories']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
