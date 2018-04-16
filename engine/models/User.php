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
use app\helpers\DateTimeHelper;
use app\helpers\StringHelper;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;


/**
 * Class User
 * @package app\models
 */
class User extends \app\models\auto\User implements IdentityInterface
{

    public $confirm_password;

    public $fake_password;

    public $confirm_email;

    public $avatarUpload;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'first_name', 'last_name'], 'trim'],
            ['email', 'unique'],
            ['email', 'email'],
            [['status'], 'safe'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 100],
            [['timezone'], 'in', 'range' => array_keys(DateTimeHelper::getTimeZones())],
            [['avatarUpload'],
                'file',
                'mimeTypes' => 'image/jpeg, image/png, image/jpeg',
                'checkExtensionByMimeType' => true,
                'wrongMimeType' => t('app','Please try to upload an image.')
            ],
            [['fake_password', 'confirm_password'], 'string', 'length' => [6, 100]],
            [['group_id'], 'integer'],
            [['first_name', 'last_name', 'email'], 'required'],
            [['fake_password'], 'required', 'on' => self::SCENARIO_CREATE],
            [['confirm_password'], 'compare', 'compareAttribute' => 'fake_password'],
            [['confirm_email'], 'compare', 'compareAttribute' => 'email'],

            [['confirm_password', 'confirm_email'], 'required', 'on' => [
                self::SCENARIO_CREATE,
            ]],
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'first_name'        => t('app', 'First Name'),
            'last_name'         => t('app', 'Last Name'),
            'email'             => t('app', 'Email'),
            'confirm_email'     => t('app', 'Confirm Email'),
            'confirm_password'  => t('app', 'Confirm Password'),
            'fake_password'     => t('app', 'Password'),
            'group_id'          => t('app', 'Group'),
            'timezone'          => t('app', 'Timezone'),
            'created_at'        => t('app', 'Created At'),
            'updated_at'        => t('app', 'Updated At'),
        ]);
    }

    /**
     * @param $user_uid
     * @return mixed
     */
    public function findByUid($user_uid)
    {
        return $this->find()->where(array(
            'user_uid' => $user_uid,
        ))->one();
    }

    /**
     * @return mixed
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
        return $this->user_uid;
    }

    /**
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->fake_password)) {
            $this->password = app()->getSecurity()->generatePasswordHash($this->fake_password);
        }


        if ($this->isNewRecord) {
            $this->user_uid = $this->generateUid();
        }

        return true;
    }


    /**
     * afterValidate
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $this->handleUploadedImage();
    }

    /**
     * @return bool
     */
    public function deactivate()
    {
        $this->status = self::STATUS_DEACTIVATED;
        $this->save(false);
        return true;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($user_id)
    {
        return static::findOne($user_id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => 'active']);
    }

    /**
     * Finds user by email
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset key
     *
     * @param $key
     * @return mixed
     */
    public static function findByPasswordResetKey($key)
    {
        return static::findOne(['password_reset_key' => $key, 'status' => 'active']);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return security()->validatePassword($password, $this->password);
    }

    /**
     * Creates a password hash
     *
     * @param  string  $password password to validate
     * @return string hashed password
     */
    public static function hashPassword($password)
    {
        return security()->generatePasswordHash($password);
    }


    /**
     * @param $route
     * @return bool
     */
    public function canAccess($route)
    {
        $access = GroupRouteAccess::findOne([
            'route'     =>$route,
            'group_id'  =>$this->group_id,
        ]);
        if(empty($access)){
            return true;
        }
        return $access->access == GroupRouteAccess::ALLOW;
    }

    /**
     * @return bool
     */
    public function sendRegistrationEmail()
    {
        if (!($model = static::findByEmail($this->email))) {
            return false;
        }

        return app()->mailSystem->add('admin-registration-confirmation', ['admin_email' => $model->email]);
    }


    /**
     * handleUploadedImage
     */
    protected function handleUploadedImage()
    {
        if ($this->hasErrors()) {
            return;
        }

        if (!($avatarUpload = UploadedFile::getInstance($this, 'avatarUpload'))) {
            return;
        }

        $storagePath = Yii::getAlias('@webroot/uploads/images/avatar');
        if (!file_exists($storagePath) || !is_dir($storagePath)) {
            if (!@mkdir($storagePath, 0777, true)) {
                notify()->addError(t('app', 'The images storage directory({path} does not exists and cannot be created!', ['path' =>$storagePath]));
                return;
            }
        }

        $newAvatarName = $avatarUpload->name;
        $avatarUpload->saveAs($storagePath . '/' . $newAvatarName);
        if (!is_file($storagePath . '/' . $newAvatarName)) {
            notify()->addError(t('app', 'Cannot move the avatar into the correct storage folder!'));
            return;
        }
        $existing_file = $storagePath . '/' . $newAvatarName;
        $newAvatarName = substr(sha1(time()),0,6) . $newAvatarName;
        copy($existing_file , $storagePath . '/' . $newAvatarName );
        $avatar = str_replace('Upload', '', $avatarUpload);
        $this->avatar = Yii::getAlias('@web/uploads/images/avatar/' . $newAvatarName);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return trim(sprintf('%s %s', $this->first_name, $this->last_name));
    }

    /**
     * Get list of all users as array of user id and full name
     *
     * @return array [user_id => user_full_name]
     */
    public static function getListOfUsers()
    {
        $result = [];

        $users = self::find()->all();
        foreach ($users as $user) {
            $result[$user->user_id] = $user->getFullName();
        }

        return $result;
    }
}
