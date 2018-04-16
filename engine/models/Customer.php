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

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use app\helpers\StringHelper;
use yii\helpers\ArrayHelper;

/**
 * Class Customer
 * @package app\models
 */
class Customer extends \app\models\auto\Customer implements IdentityInterface
{
    // when inactive model
    const STATUS_INACTIVE = 'inactive';

    // when active model
    const STATUS_ACTIVE = 'active';

    // when deactivated
    const STATUS_DEACTIVATED = 'deactivated';

    /**
     * @var string
     */
    public $passwordConfirm = '';

    /**
     * @var string
     */
    public $passwordCurrent = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var string
     */
    public $newEmail = '';

    /**
     * @var int
     */
    public $birthdayYear = 0;

    /**
     * @var int
     */
    public $birthdayMonth = 0;

    /**
     * @var int
     */
    public $birthdayDay = 0;

    /**
     * @var string
     */
    public $reCaptcha = '';

    /**
     * @var bool
     */
    private $_customer = false;

    /**
     * @var
     */
    public $_adsCount;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['email', 'first_name', 'last_name', 'phone'], 'trim'],
            [['newEmail'], 'unique', 'targetAttribute' => 'email'],
            [['newEmail'], 'email'],
            [['status'], 'safe'],
            [['birthdayYear', 'birthdayMonth', 'birthdayDay'], 'integer'],
            [['gender'], 'string', 'max' => 1],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 100],
            [['password'], 'string', 'length' => [6, 100]],

            [['first_name', 'last_name', 'email', 'password', 'passwordConfirm'], 'required', 'on' => self::SCENARIO_CREATE],
            [['passwordConfirm'], 'compare', 'compareAttribute' => 'password', 'on' => self::SCENARIO_CREATE],


            [['first_name', 'last_name', 'email', 'password', 'passwordConfirm'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
            [['passwordConfirm'], 'compare', 'compareAttribute' => 'password', 'on' => self::SCENARIO_ADMIN_CREATE],

            [['email'], 'required', 'on' => 'createFB'],

            [['first_name', 'last_name', 'email'], 'required', 'on' => self::SCENARIO_UPDATE],

            [['password', 'passwordConfirm', 'passwordCurrent'], 'required', 'on' => 'update_password'],
            [['passwordCurrent'], 'validatePasswordCurrent', 'on' => 'update_password'],
            [['password','passwordConfirm'], 'string', 'length' => [6, 100], 'on' => 'update_password'],
            [['passwordConfirm'], 'compare', 'compareAttribute' => 'password', 'on' => 'update_password'],

            [['passwordCurrent'], 'required', 'on' => 'update_email'],
            [['passwordCurrent'], 'validatePasswordCurrent', 'on' => 'update_email'],

            [['phone', 'email'], 'required', 'on' => 'post_ad'],
    
        ];

        if ($captchaSecretKey = options()->get('app.settings.common.captchaSecretKey', '')) {
           $rules[] = [
               ['reCaptcha'],
               \himiklab\yii2\recaptcha\ReCaptchaValidator::className(),
               'secret' => $captchaSecretKey,
               'on' => self::SCENARIO_CREATE
           ];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'first_name'             => t('app', 'First Name'),
            'last_name'              => t('app', 'Last Name'),
            'phone'                  => t('app', 'Web address'),
            'group_id'               => t('app', 'Group'),
            'password'               => t('app', 'Password'),
            'newEmail'               => t('app', 'New Email'),
            'email'                  => t('app', 'Web address whitepaper'),
            'passwordConfirm'        => t('app', 'Confirm Password'),
            'passwordCurrent'        => t('app', 'Current Password'),
            'birthdayYear'           => t('app', 'Birthday Year'),
            'birthdayMonth'          => t('app', 'Birthday Month'),
            'birthdayDay'            => t('app', 'Birthday Day'),
            'status'                 => t('app', 'Status'),
            'adsCount'               => t('app', 'Ads Count'),
            'created_at'             => t('app', 'Created At'),
            'updated_at'             => t('app', 'Updated At'),
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->password)) {
            $this->password_hash = app()->getSecurity()->generatePasswordHash($this->password);
        }

        if(!empty($this->newEmail)){
            $this->email = $this->newEmail;
        }

        if ($this->isNewRecord) {
            $this->customer_uid = $this->generateUid();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        parent::beforeDelete();

        $ads = Listing::find()->where(['customer_id' => $this->customer_id])->all();
        foreach ($ads as $value) {
            $value->delete();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function afterValidate()
    {
        parent::afterValidate();

        if(!empty($this->birthdayDay) && !empty($this->birthdayMonth) && !empty($this->birthdayYear))
        {
            $date = date_create_from_format('d/m/Y', $this->birthdayDay . '/' . $this->birthdayMonth . '/' . $this->birthdayYear);
            $this->birthday = $date->format('Y-m-d H:i:s');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function afterFind()
    {
        parent::afterFind();
        if(!empty($this->birthday))
        {
            $this->birthdayDay      = date('d', strtotime($this->birthday));
            $this->birthdayMonth    = date('m', strtotime($this->birthday));
            $this->birthdayYear     = date('Y', strtotime($this->birthday));
        }
        return true;
    }

    /**
     * @param $customer_uid
     * @return auto\Customer|array|null
     */
    public function findByUid($customer_uid)
    {
        return $this->find()->where(array(
            'customer_uid' => $customer_uid,
        ))->one();
    }

    /**
     * @return string
     */
    public function generateUid()
    {
        $unique = StringHelper::uniqid();
        $exists = $this->findByUid($unique);

        if (!empty($exists)) {
            return $this->generateUid();
        }

        return $unique;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->customer_uid;
    }

    /**
     * @return bool
     */
    public function activate()
    {
        if ($this->status == self::STATUS_DEACTIVATED || $this->status == self::STATUS_INACTIVE) {
            $this->status = self::STATUS_ACTIVE;
            $this->save(false);
        }
        return true;
    }

    /**
     * @return bool
     */
    public function deactivate()
    {
        if ($this->status == self::STATUS_ACTIVE){
            $this->status = self::STATUS_INACTIVE;
            $this->save(false);
        }
        return true;
    }

    /**
     * @param null $group_id
     * @return bool
     */
    public function setGroupId($group_id = null){
        if($group_id !== null && $group_id != ''){
            $this->group_id = $group_id;
            $this->save(false);

            return true;
        }
        return false;
    }


    /**
     * @param int|string $customer_id
     * @return static
     */
    public static function findIdentity($customer_id)
    {
        return static::findOne($customer_id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => 'active']);
    }

    /**
     * @param $email
     * @return static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @param $key
     * @return static
     */
    public static function findByPasswordResetKey($key)
    {
        return static::findOne(['password_reset_key' => $key, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->customer_id;
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePasswordCurrent($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = $this->getCustomer();

            if (!$customer || !(security()->validatePassword($this->passwordCurrent, $customer->password_hash))) {
                $this->addError($attribute, t('app', 'Incorrect password.'));
            }
        }
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return security()->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return security()->generatePasswordHash($password);
    }

    /**
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return app()->customer->login($this->getCustomer(), 0);
        }
        return false;
    }


    /**
     * @return Customer|bool
     */
    public function getCustomer()
    {
        if ($this->_customer === false) {
            $this->_customer = Customer::findByEmail($this->email);
        }

        return $this->_customer;
    }

    /**
     * @return bool
     */
    public function sendRegistrationEmail()
    {
        if (!($model = Customer::findByEmail($this->email))) {
            return false;
        }

        return app()->mailSystem->add('registration-confirmation', ['customer_email' => $model->email]);
    }

    /**
     * @return int|string
     */
    public function getAdsCount(){
        if($this->_adsCount !== null){
            return $this->_adsCount;
        }
        if(empty($this->customer_id)){
            return null;
        }
        $this->setAdsCount(\app\models\Listing::find()->where(['customer_id'=>$this->customer_id])->count());
        return $this->_adsCount;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setAdsCount($count = null){
        if($count !== null && $count != ''){
            $this->_adsCount = $count;
        }
        return $this;
    }

    /**
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE   => t('app', 'Active'),
            self::STATUS_INACTIVE => t('app', 'Inactive'),
            self::STATUS_DEACTIVATED  => t('app', 'Deactivated'),
        ];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return trim(sprintf('%s %s', $this->first_name, $this->last_name));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasOne(CustomerStore::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @param $limit
     * @return bool
     */
    public function hasReachedFreePostingLimit($limit)
    {
        $customerNumberAds = Listing::find()
            ->innerJoinWith('package')
            ->where(['customer_id' => $this->customer_id, 'status' => 'active', '{{%listing_package}}.price' => 0])
            ->count();

        return ($limit <= (int)$customerNumberAds) ? true : false;
    }

}