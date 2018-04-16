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
use app\models\Customer;
use app\models\ListingPackage;
use app\models\ListingStat;
use app\models\Listing;
use app\models\ListingSearch;
use app\models\Location;
use app\models\ListingImage;
use app\models\CategoryField;
use app\models\CategoryFieldValue;
use app\models\Order;
use app\models\Zone;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\db\Expression;


/**
 * Controls the actions for ads section
 *
 * @Class AdsController
 * @package app\modules\admin\controllers
 */
class ListingsController extends \app\modules\admin\yii\web\Controller
{

    /**
     * Lists all Ads
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ListingSearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->pagination->pageSize=10;
        $dataProvider->setSort([
            'defaultOrder' => ['listing_id'=>SORT_DESC]
        ]);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays stats for a specific ad
     *
     * @param $id
     * @return string
     */
    public function actionStats($id)
    {
        return $this->render('stats', [
            'model' => $this->findStat($id),
            'ad' => $this->findModel($id),
        ]);
    }

    /**
     * Lists all Ads with pending status
     *
     * @return string
     */
    public function actionPendings()
    {
        $searchModel = new ListingSearch();
        $dataProvider = $searchModel->search(request()->queryParams);
        $dataProvider->query->andWhere(['status' => Listing::STATUS_PENDING_APPROVAL]);
        $dataProvider->pagination->pageSize=10;
        return $this->render('list-pendings', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $ad = Listing::find()->where(['listing_id' => $id])->one();
        $ad->delete();
        notify()->addSuccess(t('app','Your action is complete.'));

        return $this->redirect(['/admin/listings']);
    }

    /**
     * Handles the activation for a specific ad
     *
     * @param $id
     * @return void|\yii\web\Response
     */
    public function actionActivate($id)
    {
        $error = false;
        try {
            $model = $this->findModel($id);
            Listing::sendAdActivateEmail($model);
            $model->status = Listing::STATUS_ACTIVE;
            if (!$model->save(false)) {
                throw new \Exception(t('app', 'Couldn\'t complete your action, please try again later.'));
            }

            $order = Order::find()->where(['listing_id' => $id])->one();
            $order->status = Order::STATUS_PAID;
            if (!$order->save(false)) {
                throw new \Exception(t('app', 'Couldn\'t activate the order, please try again later.'));
            }
        } catch (\Exception $e){
            notify()->addError($e->getMessage());
            $error = true;
            $this->redirect(request()->referrer);
        }

        if (!$error) {
            notify()->addSuccess(t('app','Your action is complete.'));
            $this->redirect(request()->referrer);
        }
    }

    /**
     * Handles the deactivation for a specific ad
     *
     * @param $id
     * @return void|\yii\web\Response
     */
    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        Listing::sendAdDeactivateEmail($model);
        $model->status = Listing::STATUS_DEACTIVATED;
        if (!$model->save(false)) {
            notify()->addError(t('app','Couldn\'t complete your action, please try again later.'));
            return;
        }
        notify()->addSuccess(t('app','Your action is complete.'));
        return $this->redirect(request()->referrer);
    }

    /**
     * @param $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $customer = Customer::findOne($model->customer_id);
        $customer->scenario = Customer::SCENARIO_UPDATE;
        $uploadedImages = $model->images;
        $images = new ListingImage();
        $location = Location::findOne($model->location_id);
        $package = ListingPackage::findOne($model->package_id);
        $fields = $model->categoryFieldValues;

        if ($model->load(request()->post()) &&
            $customer->load(request()->post()) &&
            $location->load(request()->post())
        ){
            $transaction = db()->beginTransaction();
            $error = false;
            try {
                $oldFields = $model->categoryFieldValues;
                if (!empty($oldFields)) {
                    foreach ($oldFields as $oldField) {
                        $oldField->delete();
                    }
                }

                $fieldsPost = request()->post('CategoryField');
                if (!empty($fieldsPost)) {
                    foreach ($fieldsPost as $fieldId => $fieldPost) {
                        $newFields = new CategoryFieldValue();
                        $newFields->field_id = (int)$fieldId;
                        $newFields->listing_id = (int)$id;
                        $newFields->value = $fieldPost;
                        $newFields->save();
                    }
                }

                $dirtyAttributes = $model->getDirtyAttributes();
                $oldPackageId = $model->getOldAttribute('package_id');

                if ((isset($dirtyAttributes['status']) ? $dirtyAttributes['status'] : '') === 'active' ||
                    (isset($dirtyAttributes['package_id']) ? (int)$dirtyAttributes['package_id'] : null) !== $oldPackageId)
                {
                    $newPackage = ListingPackage::findOne((int)$dirtyAttributes['package_id']);
                    $model->promo_expire_at = null;
                    $model->listing_expire_at = new Expression('NOW() + INTERVAL ' . (int)$newPackage->listing_days . ' DAY');
                    if ((int)$newPackage->promo_days > 0) {
                        $model->promo_expire_at = new Expression('NOW() + INTERVAL ' . (int)$newPackage->promo_days . ' DAY');
                    }
                    $model->remaining_auto_renewal = 0;
                    if ((int)$newPackage->auto_renewal > 0) {
                        $model->remaining_auto_renewal = $newPackage->auto_renewal;
                    }
                }

                $location->save();
                $model->location_id = $location->location_id;
                $model->save();
                $customer->save();

                $transaction->commit();
            } catch (\Exception $e) {
                notify()->addError($e->getMessage());
                $transaction->rollBack();
                $error = true;
            }
            if (!$error) {

                if (request()->post('send_email') === '1') {
                   app()->mailSystem->add('ad-was-updated-by-admin', ['listing_id' => $model->listing_id]);
                }

                notify()->addSuccess(t('app','Your action is complete.'));
                return $this->redirect(['/admin/listings']);
            } else {
                notify()->addError(t('app','Something went wrong!'));
                return $this->redirect(['/admin/listings']);
            }
        } else {
            return $this->render('form', [
                'model'             => $model,
                'customer'          => $customer,
                'location'          => $location,
                'fields'            => $fields,
                'images'            => $images,
                'package'           => $package,
                'uploadedImages'    => $uploadedImages,
                'image_random_key'  => substr(sha1(time()), 0, 8),
            ]);
        }
    }

    /**
     * @return array|Response
     */
    public function actionRemoveAdImage()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['admin/listings/']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $imageId = request()->post('key');
        if (empty($imageId)) {
            return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
        }
        $image = ListingImage::findOne(['image_id' => $imageId]);
        if (empty($image)) {
            return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
        }

        if ($image->delete()) {
            return ['result' => 'success', 'html' => $image];
        }

        return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
    }

    /**
     * @return array|Response
     */
    public function actionSortAdImages()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['admin/listings/']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $images = request()->post('images');
        $images = json_decode($images);

        if (empty($images)) {
            return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
        }

        foreach ($images as $sortOrder => $image) {
            $_image = ListingImage::findOne(['image_id' => $image->key]);
            $_image->sort_order = $sortOrder + 1;
            $_image->save(false);
        }

        return ['result' => 'success'];
    }

    /**
     * @return array|Response
     * @throws \Exception
     */
    public function actionUploadImage()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
        }
        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $fileId = request()->post('file_id');
        $adId = request()->post('adId');
        $image_form_key = request()->post('image_form_key');

        $UploadedImages = ListingImage::find()
            ->select('sort_order')
            ->where(['listing_id' => $adId])
            ->orderBy('sort_order DESC')
            ->one();

        (!$UploadedImages) ? $sort = $fileId : $sort = $UploadedImages->sort_order + $fileId;

        $images = new ListingImage();
        if(!($imagesGallery = UploadedFile::getInstances($images, 'imagesGallery'))){
            throw new \Exception(t('app', 'Please add at least one image to your ad post'));
        }

        return $images->uploadImageByAjax($imagesGallery, $image_form_key, $adId, $sort);
    }


    /**
     * View a specific ad in frontend
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionAdminPreviewAd($id)
    {
        $ad = Listing::findOne(['listing_id' => (int)$id]);
        if (empty($ad)) {
            notify()->addWarning(t('app', 'Please specify a valid ad'));
            return $this->redirect(['/admin/listings']);
        }

        app()->customer->switchIdentity($ad->customer);

        session()->set('impersonating_customer', true);
        if ($ad->status == Listing::STATUS_PENDING_APPROVAL) {
            session()->set('impersonating_customer_back_url', url(['/admin/listings/pendings']));
        }
        session()->set('impersonating_customer_back_url', url(['/admin/listings']));
        return $this->redirect(['/listing/preview/' . $ad->slug]);
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Listing::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findStat($id)
    {
        if (($model = ListingStat::findOne($id)) !== null) {
            return $model;
        }
        return false;
    }

    /**
     * @return array|Response
     */
    public function actionGetCountryZones()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['/admin/listings']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $countryId = request()->post('country_id');
        if (empty($countryId)) {
            return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
        }

        $countryZones = Zone::find()->select(['zone_id', 'name'])->where(['country_id' => (int)$countryId, 'status' => Zone::STATUS_ACTIVE])->all();

        return ['result' => 'success', 'response' => $countryZones];
    }

    /**
     * @return array|Response
     */
    public function actionGetCategoryFields()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['/admin/listings/']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;
        $categoryId = request()->post('category_id');
        $adId = request()->post('listing_id');

        $categoryFields = CategoryField::find()->where(['category_id' => $categoryId])->orderBy(['sort_order' => SORT_ASC])->all();

        $typeData = [];
        foreach ($categoryFields as $field) {
            $typeData[] = [
                'type'  => $field->type,
                'field' => $field,
            ];
        }

        foreach ($typeData as $data) {
            $type = $data['type'];
            $field = $data['field'];
            if (!is_file(\Yii::getAlias('@' . str_replace('\\', '/', $type->class_name) . '.php'))) {
                continue;
            }
            $className = $type->class_name;
            $component = new $className();

            if (!($component instanceof Type)) {
                continue;
            }

            $component->field = $field;
            $component->params = [
                'categoryId' => $categoryId,
                'adId'       => $adId,
            ];
            $component->handleFrontendFormDisplay();
        }

        return ['result' => 'success', 'html' => $this->renderPartial('custom-fields')];
    }

}
