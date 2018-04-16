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

use yii\base\Model;

/**
 * SendMessage is the model behind the send message form. Full name, Phone, Email, Message and reCaptcha
 */
class ContactForm extends Model
{
    /**
     *@var string
     */
    public $fullName = '';

    /**
     * @var int
     */
    public $phone = '';

    /**
     * @var string
     */
    public $email = '';

    /**
     * @var string
     */
    public $message = '';

    /**
     * @var string
     */
    public $reCaptcha = '';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        $rules = [
            [['email', 'fullName', 'message'], 'required'],
            ['email', 'email'],
            ['phone', 'number'],
            ['phone', 'string', 'max' => 15, 'min' => 8,'message' => t('app', 'Phone must have maxim 15 digits')],
            ['message', 'string', 'max' => 1000],
            [['email', 'fullName'], 'string', 'max' => 100],
            [['slug'], 'string', 'max' => 110],
        ];

        if ($captchaSecretKey = options()->get('app.settings.common.captchaSecretKey', '')) {
            $rules[] = [
                ['reCaptcha'],
                \himiklab\yii2\recaptcha\ReCaptchaValidator::className(),
                'secret' => $captchaSecretKey
            ];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => t('app', 'Email'),
            'fullName' => t('app', 'Full Name'),
            'message'  => t('app', 'Message'),
            'phone'    => t('app', 'Phone')
        ];
    }

    public static function contactPageAttribute(){

        return [
            'page_id'     => 'contact-form',
            'title'       => t('app', 'Contact'),
            'slug'        => url(['/site/contact']),
            'section'     => (int)options()->get('app.content.contact.section', 0),
            'sort_order'  => (int)options()->get('app.content.contact.sort_order', 0),
        ];

    }


}
