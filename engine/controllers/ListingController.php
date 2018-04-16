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

namespace app\controllers;

use app\components\mail\template\TemplateTypeAdConversation;
use app\fieldbuilder\Type;
use app\models\Listing;
use app\models\ListingFavorite;
use app\models\ListingImage;
use app\models\ListingPackage;
use app\models\ListingStat;
use app\models\Category;
use app\models\CategoryField;
use app\models\CategoryFieldValue;
use app\models\Conversation;
use app\models\ConversationMessage;
use app\models\Customer;
use app\models\Gateway;
use app\models\Location;
use app\models\Order;
use app\models\OrderTax;
use app\models\OrderTransaction;
use app\models\Tax;
use app\models\Zone;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\yii\web\Controller;
use app\helpers\CommonHelper;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * Class ListingController
 * @package app\controllers
 */
class ListingController extends Controller
{
    /**
     * init
     */
    public function init()
    {
        parent::init();
        app()->trigger('app.controller.ad.init');
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'send-message' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($slug)
    {
        $ad = $this->findAdBySlug($slug);

        if (!$ad->isActive && !$ad->isExpired) {
            if (!$ad->isOwnedBy(app()->customer->id)) {
                throw new NotFoundHttpException(t('app', 'The ICO advertisement is not active yet, please try again later.'));
            }
            notify()->addWarning(t('app', 'Your ICO advertisement is not active yet and it\'s visible only to you!'));
        }

        if ($ad->isExpired) {
            notify()->addWarning(t('app', 'This ICO advertisement has expired!'));
        }

        app()->view->registerMetaTag([
            'name'    => 'description',
            'content' => $ad->metaDescription
        ]);

        ListingStat::track(ListingStat::TOTAL_VIEWS, $ad->listing_id);

        app()->view->title = $ad->title;

        // hook action for adding ad image to facebook meta object in head
        $mainImage = $ad->mainImage->image;
        app()->on('app.header.beforeScripts', function () use ($mainImage, $ad) {
            echo '<meta property="fb:app_id" content="' . options()->get('app.settings.common.siteFacebookId', '') . '"/>';
            echo '<meta property="og:image" content="' . Url::base(true) . $mainImage . '"/>';
            echo '<meta property="og:title" content="' . $ad->title . '"/>';
            echo '<meta property="og:url" content="' . url(['/listing/index', 'slug' => $ad->slug], true) . '"/>';
            echo '<meta property="og:description" content="' . $ad->metaDescription . '"/>';
            echo '<meta property="og:type" content="article"/>';
        });

        // #INFO
        // relation with getRelation gets the whole ActiveQuery, that you can manipulate
        // if you get by $ad->relation, you will have a result of ActiveRecord

        return $this->render('view', [
            'ad'         => $ad,
            'images'     => $ad->getImages()->orderBy(['sort_order' => SORT_ASC])->all(),
            'categories' => new Category(),
        ]);
    }

    /**
     * @param $slug
     * @return string|Response
     */
    public function actionPreview($slug)
    {
        if (app()->customer->isGuest == true) {
            return $this->redirect(['/']);
        }

        $ad = $this->findAdBySlug($slug);

        if (!$ad->isOwnedBy(app()->customer->id)) {
            return $this->redirect(['/']);
        }

        app()->view->title = t('app', 'Preview - ') . $ad->title;

        return $this->render('preview', [
            'ad'         => $ad,
            'images'     => $ad->getImages()->orderBy(['sort_order' => SORT_ASC])->all(),
            'categories' => new Category(),
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionPost()
    {
        if (app()->customer->isGuest) {
            notify()->addWarning(t('app', 'Please login before performing this action.'));

            return $this->redirect(['/account/login']);
        }

        $ad = new Listing();
        $images = new ListingImage();
        $location = new Location();
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_ASC])->all();
        $customer = Customer::findOne(app()->customer->id);
        $customer->scenario = 'post_ad';

        if ($ad->load(request()->post()) &&
            $location->load(request()->post()) &&
            $customer->load(request()->post()) &&
            $images->load(request()->post())
        ) {

            $transaction = db()->beginTransaction();
            $error = false;
            try {
                $image_form_key = $images->image_form_key;
                $this->saveDraft($ad, $location, $customer);
                $images->matchListingId($ad->listing_id, $image_form_key);
                $transaction->commit();
            } catch (\Exception $e) {
                notify()->addError($e->getMessage());
                $error = true;
                $transaction->rollBack();
            }
            if (!$error) {
                $target = request()->post('process', 'no');
                $target = key($target);

                if (options()->get('app.settings.common.skipPackages', 0) == 1 && $target=='package'){
                   $this->skipPackages($ad);
                   return false;
                }

                return $this->redirect(url(['/listing/' . $target, 'slug' => $ad->slug]));
            }
        }

        app()->view->title = t('app', 'Post Ad') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('form', [
            'action'     => 'create',
            'ad'         => $ad,
            'images'     => $images,
            'location'   => $location,
            'categories' => $categories,
            'customer'   => $customer,
            'image_random_key'  => substr(sha1(time()), 0, 8),
        ]);
    }

    /**
     * @param $slug
     * @return string|Response
     */
    public function actionUpdate($slug)
    {
        if (app()->customer->isGuest) {
            return $this->redirect(['/listing/post']);
        }

        $ad = Listing::find()->where(['slug' => $slug])->one();
        if (empty($ad)) {
            return $this->redirect(['/listing/post']);
        }

        $customer = $ad->customer;
        $customer->scenario = 'post_ad';
        if (!$ad->isOwnedBy(app()->customer->id)) {
            return $this->redirect(['/listing/post']);
        }

        $uploadedImages = $ad->images;
        $images = new ListingImage();
        $location = $ad->location;
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->orderBy(['sort_order' => SORT_ASC])->all();
        $fields = $ad->categoryFieldValues;

        $adClone = clone $ad;

        if ($ad->load(request()->post()) &&
            $location->load(request()->post()) &&
            $customer->load(request()->post()) &&
            $images->load(request()->post())
        ) {
            $transaction = db()->beginTransaction();
            $error = false;
            try {
                $this->saveDraft($ad, $location, $customer);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                notify()->addError($e->getMessage());
                $error = true;
            }
            if (!$error) {
                $target = request()->post('process', 'no');
                $target = key($target);

                if ($target == 'update-info') {
                    $this->updateInfo($ad);
                    notify()->addSuccess(t('app', 'The information was updated successfully.'));
                    return $this->redirect(url(['/listing/index/', 'slug' => $ad->slug]));
                }

                if (options()->get('app.settings.common.skipPackages', 0) == 1 && $target=='package'){
                    $this->skipPackages($ad);
                    return false;
                }

                return $this->redirect(url(['/listing/' . $target, 'slug' => $ad->slug]));
            }

            $ad->status = $adClone->status;
        }

        unset($adClone);

        app()->view->title = t('app', 'Update Ad') . ' - ' . options()->get('app.settings.common.siteName', 'EasyAds');

        return $this->render('form', [
            'action'         => 'update',
            'ad'             => $ad,
            'images'         => $images,
            'location'       => $location,
            'fields'         => $fields,
            'categories'     => $categories,
            'customer'       => $customer,
            'uploadedImages' => $uploadedImages,
            'image_random_key'  => substr(sha1(time()), 0, 8),
        ]);
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
        $action = request()->post('action');
        $image_form_key = request()->post('image_form_key');

        if($action === 'update') {
            $ad = Listing::findOne($adId);
            if (!$ad->isOwnedBy(app()->customer->id)) {
                return $this->redirect(['/listing/post']);
            }
            $UploadedImages = ListingImage::find()
                ->select('sort_order')
                ->where(['listing_id' => $adId])
                ->orderBy('sort_order DESC')
                ->one();

            (!$UploadedImages) ? $sort = $fileId : $sort = $UploadedImages->sort_order + $fileId;

        } else {
            $UploadedImages = ListingImage::find()
                ->select('sort_order')
                ->where(['image_form_key' => $image_form_key])
                ->orderBy('sort_order DESC')
                ->one();

            (!$UploadedImages) ? $sort = $fileId : $sort = $UploadedImages->sort_order + $fileId;
        }

        $images = new ListingImage();
        if(!($imagesGallery = UploadedFile::getInstances($images, 'imagesGallery'))){
            throw new \Exception(t('app', 'Please add at least your logo to your ICO advertisement post'));
        }

        return $images->uploadImageByAjax($imagesGallery, $image_form_key, $adId, $sort);
    }


    protected function updateInfo($ad)
    {
        $ad->status = Listing::STATUS_ACTIVE;
        $ad->save(false);
    }

    /**
     * This is used only for free checkout when is enabled and will skip packages page.
     * @param $ad
     * @return Response
     */
    protected function skipPackages($ad)
    {
        if (empty($ad)) {
            return $this->redirect(['/listing/post']);
        }

        $customer = $ad->customer;
        $customer->scenario = 'post_ad';
        if (!$ad->isOwnedBy(app()->customer->id)) {
            return $this->redirect(['/listing/post']);
        }

        if (request()->post()) {
            $freeAdsLimit = (int)options()->get('app.settings.common.freeAdsLimit', 9999);
            if ($customer->hasReachedFreePostingLimit($freeAdsLimit))
            {
                notify()->addWarning(t('app', 'You have reached the limit for free ads, please contact us if you need more details!'));
                return $this->redirect(['/account/my-listings']);
            }

            $transaction = db()->beginTransaction();
            $error = false;
            try {
                $packageId = options()->get('app.settings.common.defaultPackage', 0);

                $package = ListingPackage::find()->where(['package_id' => $packageId])->one();
                $locationTax = Tax::calculateTax($ad->location->country_id, $ad->location->zone_id, $package->price);
                $cart = CommonHelper::getCartTotal($locationTax, $package->price);

                //make new order
                $order = Order::createNew($ad, $package, $cart);
                if (empty($order)) {
                    throw new \Exception(t('app', 'Something went wrong with your order, please try again later.'));
                }
                //populate OrderTax
                if (!empty($locationTax)) {
                    foreach ($locationTax as $tax) {

                        $orderTax = new OrderTax();
                        $orderTax->order_id = $order->order_id;
                        $orderTax->tax_name = $tax->name;
                        $orderTax->tax_percent = $tax->percent;
                        $orderTax->tax_price = $tax->price;
                        $orderTax->save(false);
                    }
                }

                $order->status = Order::STATUS_PAID;
                $order->save(false);

                //update ad dates
                $ad->promo_expire_at = null;
                $ad->listing_expire_at = new Expression('NOW() + INTERVAL ' . (int)$package->listing_days . ' DAY');
                if ((int)$package->promo_days > 0) {
                    $ad->promo_expire_at = new Expression('NOW() + INTERVAL ' . (int)$package->promo_days . ' DAY');
                }

                $ad->remaining_auto_renewal = 0;
                if ((int)$package->auto_renewal > 0) {
                    $ad->remaining_auto_renewal = $package->auto_renewal;
                }

                //Check admin approval option to set the right status for ad
                $adminApproval = options()->get('app.settings.common.adminApprovalAds', 1);
                if ($adminApproval > 0) {
                    $ad->status = Listing::STATUS_PENDING_APPROVAL;
                    Listing::sendWaitingApprovalEmail($ad);
                } else {
                    Listing::sendAdActivateEmail($ad);
                    $ad->status = Listing::STATUS_ACTIVE;
                }

                $ad->package_id = $packageId;
                $ad->save(false);
                $transaction->commit();
            } catch (\Exception $e) {
                notify()->addError($e->getMessage());
                $error = true;
                $transaction->rollBack();
            }

            if (!$error) {
                notify()->addSuccess(t('app', 'Your ICO advertisement was successfully created and will be active within two days if you have chosen priority listing. Otherwise it will be active within 15 days.'));
                return $this->redirect(['/account/my-listings']);
            }
        }
    }

    /**
     * @param $ad
     * @param $location
     * @param $customer
     * @return mixed
     * @throws \Exception
     */
    protected function saveDraft($ad, $location, $customer)
    {
        if (!($location->save())) {
            throw new \Exception(t('app', 'Your form has a few errors, please fix them and try again!'));
        }
        if (!($customer->save())) {
            throw new \Exception(t('app', 'Your form has a few errors, please fix them and try again!'));
        }
        $ad->location_id = $location->location_id;
        $ad->status = $ad::STATUS_DRAFT;

        if (!$ad->save()) {
            throw new \Exception(t('app', 'Your form has a few errors, please fix them and try again!'));
        }
        unset($adClone);

        $oldFields = $ad->categoryFieldValues;
        if (!empty($oldFields)) {
            foreach ($oldFields as $oldField) {
                $oldField->delete();
            }
        }

        $fieldsPost = request()->post('CategoryField');
        if (!empty($fieldsPost)) {
            foreach ($fieldsPost as $fieldId => $fieldPost) {
                $fields = new CategoryFieldValue();
                $fields->field_id = (int)$fieldId;
                $fields->listing_id = (int)$ad->listing_id;
                $fields->value = $fieldPost;
                $fields->save();
            }
        }

        return $ad->listing_id;
    }


    /**
     * @param $slug
     * @return string|Response
     */
    public function actionPackage($slug)
    {
        if (app()->customer->isGuest) {
            return $this->redirect(['/listing/post']);
        }

        $ad = Listing::find()->where(['slug' => $slug])->one();
        if (empty($ad)) {
            return $this->redirect(['/listing/post']);
        }

        $customer = $ad->customer;
        $customer->scenario = 'post_ad';
        if (!$ad->isOwnedBy(app()->customer->id)) {
            return $this->redirect(['/listing/post']);
        }

        $location = $ad->location;
        $packages = ListingPackage::find()->all();

        if (request()->post()) {
            $adPost = request()->post('Listing');
            $packageId = ArrayHelper::getValue($adPost, 'package_id');
            $package = ListingPackage::find()->where(['package_id' => $packageId])->one();
            $freeAdsLimit = (int)options()->get('app.settings.common.freeAdsLimit', 9999);
            //Check if customer have reach maxim number of free ads and if he choose a free package.
            if ($customer->hasReachedFreePostingLimit($freeAdsLimit) && $package->price == 0 )
            {
                notify()->addWarning(t('app', 'You have reached the limit for free ads, please choose another package!'));
                return $this->redirect(url(['/listing/package/' . $slug]));
            }

            $transaction = db()->beginTransaction();
            $error = false;
            try {
                if (empty($packageId)) {
                    throw new \Exception(t('app', 'Please select a package first to finish your posting.'));
                }

                $locationTax = Tax::calculateTax($ad->location->country_id, $ad->location->zone_id, $package->price);
                $cart = CommonHelper::getCartTotal($locationTax, $package->price);
                $cartTotal = $cart['total'];

                //make new order
                $order = Order::createNew($ad, $package, $cart);
                if (empty($order)) {
                    throw new \Exception(t('app', 'Something went wrong with your order, please try again later.'));
                }
                //populate OrderTax
                if (!empty($locationTax)) {
                    foreach ($locationTax as $tax) {

                        $orderTax = new OrderTax();
                        $orderTax->order_id = $order->order_id;
                        $orderTax->tax_name = $tax->name;
                        $orderTax->tax_percent = $tax->percent;
                        $orderTax->tax_price = $tax->price;
                        $orderTax->save(false);
                    }
                }
                // process payment methods and purchase action in the handlePayment
                $event = new \app\yii\base\Event();
                if ($package->price > 0) {

                    //make new transaction
                    $orderTransaction = OrderTransaction::createNew($order);
                    if (empty($orderTransaction)) {
                        throw new \Exception(t('app', 'Something went wrong with your order transaction, please try again later.'));
                    }

                    if (request()->post('paymentGateway')) {
                        $event->params = [
                            'transaction' => $orderTransaction,
                            'order'       => $order,
                            'customer'    => $customer,
                            'cartTotal'   => $cartTotal,
                            'location'    => $location
                        ];
                        app()->trigger('app.controller.ad.package.handlePayment', $event);
                    }
                } else {
                    // make paid status for free packages
                    $order->status = Order::STATUS_PAID;
                    $order->save(false);
                }
                // For methods with redirect
                if (!empty($event->params['redirect'])) {
                    $ad->package_id = $packageId;
                    $ad->save(false);
                    $transaction->commit();

                    return $this->redirect($event->params['redirect']);
                }

                // Because some payment methods can not getRedirectUrl() correctly without losing data e.g. payfast
                if (!empty($event->params['response']) &&
                    is_object($event->params['response']) &&
                    method_exists($event->params['response'], 'redirect')
                ) {
                    $ad->package_id = $packageId;
                    $ad->save(false);
                    $transaction->commit();
                    notify()->addSuccess(t('app', 'Your ad was successfully created and will be active after payment validation!'));
                    $event->params['response']->redirect();

                    return;
                }


                //Continue processing if not a method with redirect
                //Check status of order after getting back from payment procedure
                if ($order->status != Order::STATUS_PAID && !$event->params['manual']) {
                    throw new \Exception(t('app', 'Something went wrong with your order verification, please try again later.'));
                }

                //update ad dates
                $ad->promo_expire_at = null;
                $ad->listing_expire_at = new Expression('NOW() + INTERVAL ' . (int)$package->listing_days . ' DAY');
                if ((int)$package->promo_days > 0) {
                    $ad->promo_expire_at = new Expression('NOW() + INTERVAL ' . (int)$package->promo_days . ' DAY');
                }

                $ad->remaining_auto_renewal = 0;
                if ((int)$package->auto_renewal > 0) {
                    $ad->remaining_auto_renewal = $package->auto_renewal;
                }

                //Check admin approval option to set the right status for ad
                $adminApproval = options()->get('app.settings.common.adminApprovalAds', 1);
                if ($adminApproval > 0 || (!empty($event->params['manual']))) {
                    $ad->status = Listing::STATUS_PENDING_APPROVAL;
                    Listing::sendWaitingApprovalEmail($ad);
                } else {
                    Listing::sendAdActivateEmail($ad);
                    $ad->status = Listing::STATUS_ACTIVE;
                }

                $ad->package_id = $packageId;
                $ad->save(false);
                $transaction->commit();
            } catch (\Exception $e) {
                notify()->addError($e->getMessage());
                $error = true;
                $transaction->rollBack();
            }

            if (!$error) {
                notify()->addSuccess(t('app', 'Your ad was successfully created and will be active soon'));

                return $this->redirect(['/account/my-listings']);
            }
        }
        app()->view->title = t('app', 'Package') . ' - ' . $ad->title;

        return $this->render('form_package', [
            'ad'       => $ad,
            'packages' => $packages,
        ]);
    }

    /**
     * @return array|Response
     */
    public function actionGetSummary()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $countryId = request()->post('country_id');
        $zoneId = request()->post('zone_id');
        $price = request()->post('price');

        $locationTax = Tax::calculateTax($countryId, $zoneId, $price);

        $cart = CommonHelper::getCartTotal($locationTax, $price);

        return ['result' => 'success', 'html' => $this->renderPartial('summary',
            [
                'subtotal' => $cart['subtotal'],
                'tax'      => $cart['locationTax'],
                'total'    => $cart['total'],
            ]
        )
        ];
    }

    /**
     * @return array|Response
     */
    public function actionGetCategoryFields()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
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

    /**
     * @return array|Response
     */
    public function actionGetCountryZones()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
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
    public function actionRemoveAdImage()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $imageId = request()->post('key');
        if (empty($imageId)) {
            return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
        }

        $image = ListingImage::findOne(['image_id' => $imageId]);
        $ad = Listing::findOne($image->listing_id);
        if (!empty($ad->listing_id) && !$ad->isOwnedBy(app()->customer->id)) {
            return ['result' => 'error', 'html' => t('app', 'You have no access!')];
        }

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
            return $this->redirect(['listing/post']);
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
     */
    public function actionGetCustomerContact()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $customerId = request()->post('customer_id');
        if (empty($customerId)) {
            return ['result' => 'error', 'html' => t('app', 'Something went wrong...')];
        }

        $customerEmail = Customer::find()->select(['email', 'phone'])->where(['customer_id' => $customerId])->one();

        return ['result' => 'success', 'response' => $customerEmail];
    }

    /**
     * @return array|Response
     */
    public function actionToggleFavorite()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        if (app()->customer->isGuest) {
            return ['result' => 'error', 'msg' => t('app', 'You need to be logged in to add favorites')];
        }

        $customer_id = app()->customer->id;
        if (empty($customer_id)) {
            return ['result' => 'error', 'msg' => t('app', 'Something went wrong...')];
        }

        $adId = request()->post('listing_id');
        $ad = Listing::findOne($adId);
        if (empty($ad)) {
            return ['result' => 'error', 'msg' => t('app', 'Something went wrong...')];
        }

        $adFavorite = ListingFavorite::find()->where(['listing_id' => $adId, 'customer_id' => $customer_id])->one();
        if (!empty($adFavorite)) {
            $adFavorite->delete();

            return ['result' => 'success', 'action' => 'removed', 'msg' => t('app', 'Removed from Favorites')];
        }

        $adFavorite = new ListingFavorite();
        $adFavorite->listing_id = $adId;
        $adFavorite->customer_id = $customer_id;
        $adFavorite->save();

        return ['result' => 'success', 'action' => 'added', 'msg' => t('app', 'Added to Favorites')];
    }

    /**
     * @return array|Response
     */
    public function actionDelete()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        if (app()->customer->isGuest) {
            return ['result' => 'error', 'msg' => t('app', 'You need to be logged in to make this action')];
        }

        $customer_id = app()->customer->id;
        if (empty($customer_id)) {
            return ['result' => 'error', 'msg' => t('app', 'Something went wrong...')];
        }

        $adId = request()->post('listing_id');
        $ad = Listing::find()->where(['listing_id' => $adId, 'customer_id' => $customer_id])->one();

        if (!$ad->isOwnedBy(app()->customer->id)) {
            return ['result' => 'error', 'html' => t('app', 'You have no access!')];
        }

        if ($ad->delete()) {
            return ['result' => 'success', 'msg' => t('app', 'Your Ad was deleted!')];
        }

        return ['result' => 'error', 'msg' => t('app', 'We couldn\'t delete your ad, please try again later')];
    }

    /**
     * @return array|Response
     */
    public function actionTrackStats()
    {
        /* allow only ajax calls */
        if (!request()->isAjax) {
            return $this->redirect(['listing/post']);
        }

        /* set the output to json */
        response()->format = Response::FORMAT_JSON;

        $adId = request()->post('listing_id');
        $statsType = request()->post('stats_type');
        $ad = Listing::find()->where(['listing_id' => $adId])->one();
        if (empty($ad)) {
            return ['result' => 'error', 'msg' => t('app', 'Something went wrong...')];
        }

        ListingStat::track($statsType, $adId);

        return ['result' => 'success'];
    }

    public function actionSendMessage()
    {
        $messageData = request()->post('SendMessageForm');
        if (empty($messageData['slug'])) {
            throw new NotFoundHttpException(t('app', 'Slug is required'));
        }

        $slug = $messageData['slug'];

        $listing = $this->findAdBySlug($slug);
        $isSuccess = false;
        $errorMessage = t('app', 'Something went wrong, message was not sent!');

        if (app()->customer->isGuest) {
            // part for guest

            $isSuccess = app()->mailSystem->add('non-user-message', [
                'listing_id'     => $listing->listing_id,
                'recipient_type' => TemplateTypeAdConversation::RECIPIENT_TYPE_SELLER,
                'buyer_name'     => $messageData['fullName'],
                'buyer_email'    => $messageData['email'],
                'message'        => $messageData['message'],
                'reply_to'       => $messageData['email'],
                'reply_url'      => '',
            ]);
        } else {
            // part for logged in user

            if (!$listing->isOwnedBy(app()->customer->id)) {
                $transaction = db()->beginTransaction();

                $conversation = Conversation::find()->where(['buyer_id' => app()->customer->id, 'listing_id' => $listing->listing_id])->one();

                try {
                    $isSavedConversation = true;
                    $isNewConversation = false;

                    // create new conversation if needed
                    if ($conversation === null) {
                        $conversation = new Conversation();
                        $conversation->buyer_id = app()->customer->id;
                        $conversation->seller_id = $listing->customer_id;
                        $conversation->listing_id = $listing->listing_id;
                        $conversation->is_buyer_blocked = Conversation::NO;
                        $conversation->status = Conversation::CONVERSATION_STATUS_ACTIVE;
                        $isSavedConversation = $conversation->save();
                        $isNewConversation = $isSavedConversation;
                    }

                    // create new message in conversation if it was retrieved or created
                    if ($isSavedConversation) {
                        $message = new ConversationMessage();
                        $message->conversation_id = $conversation->conversation_id;
                        $message->buyer_id = app()->customer->id;
                        $message->message = $messageData['message'];
                        $message->is_read = ConversationMessage::NO;
                        $isSuccess = $message->save();
                    }

                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }

                if ($isSuccess) {
                    app()->mailSystem->add($isNewConversation ? 'new-conversation' : 'you-have-unread-messages', [
                        'listing_id'     => $listing->listing_id,
                        'recipient_type' => TemplateTypeAdConversation::RECIPIENT_TYPE_SELLER,
                        'buyer_name'     => $message->buyer->fullName,
                        'buyer_email'    => $message->buyer->email,
                        'message'        => $messageData['message'],
                        'reply_url'      => Url::to(['conversation/reply', 'conversation_uid' => $conversation->conversation_uid], true),
                    ]);
                }
            } else {
                $errorMessage = t('app', "You can not send a message to yourself!");
            }
        }

        if ($isSuccess) {
            notify()->addSuccess(t('app', 'Message was successfully sent!'));
        } else {
            notify()->addError($errorMessage);
        }

        return $this->redirect(['index', 'slug' => $slug]);
    }

    /**
     * @param $slug
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findAdBySlug($slug)
    {
        if (($ad = Listing::findOne(['slug' => $slug])) !== null) {
            return $ad;
        }

        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
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
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }
}