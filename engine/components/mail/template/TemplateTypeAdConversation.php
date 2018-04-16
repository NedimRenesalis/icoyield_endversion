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

namespace app\components\mail\template;

use app\models\Listing;
use yii\base\InvalidConfigException;

class TemplateTypeAdConversation implements TemplateTypeInterface
{
    const TEMPLATE_TYPE = 5;

    // list of recipients
    const RECIPIENT_TYPE_SELLER = 1;
    const RECIPIENT_TYPE_BUYER = 2;

    /**
     * @var array list of variables of template
     */
    protected $varsList = [
        'recipient_name'        => 'Recipient Full Name',
        'recipient_email'       => 'Recipient Email',
        'sender_name'           => 'Sender Full Name',
        'sender_email'          => 'Sender Email',
        'message'               => 'Message',
        'reply_url'             => 'Reply Url',
        'ad_title'              => 'Ad title',
        'ad_url'                => 'Ad URL',
        'general_site_name'     => 'General site name',
        'general_contact_email' => 'General contact email',
    ];

    protected $recipientType;
    protected $buyerFullName;
    protected $buyerEmail;
    protected $message;
    protected $replyUrl;
    protected $adId;

    public function __construct(array $data)
    {
        if (!empty($data)) {
            $this->adId             = $data['listing_id'];
            $this->recipientType    = $data['recipient_type'];
            $this->buyerFullName    = $data['buyer_name'];
            $this->buyerEmail       = $data['buyer_email'];
            $this->message          = $data['message'];
            $this->replyUrl         = $data['reply_url'];
        }
    }

    /**
     * @inheritdoc
     */
    public function populate()
    {
        $adModel = Listing::find()->with('customer')->where(['listing_id' => $this->adId])->one();
        $result = [
            'message'               => nl2br(preg_replace("/[\r\n]+/", "\n", $this->message)),
            'reply_url'             => $this->replyUrl,
            'ad_title'              => $adModel->title,
            'ad_url'                => url(['/listing/index/', 'slug' => $adModel->slug], true),
            'general_site_name'     => options()->get('app.settings.common.siteName', 'EasyAds'),
            'general_contact_email' => options()->get('app.settings.common.siteEmail', 'contact@easyads.io'),
        ];

        // we need that to be able to send the same template to both customers
        if (self::RECIPIENT_TYPE_SELLER == $this->recipientType) {
            $this->recipient            = $adModel->customer->email;
            $result['recipient_name']   = $adModel->customer->fullName;
            $result['recipient_email']  = $adModel->customer->email;
            $result['sender_name']      = $this->buyerFullName;
            $result['sender_email']     = $this->buyerEmail;
        } else if (self::RECIPIENT_TYPE_BUYER == $this->recipientType) {
            $this->recipient            = $this->buyerEmail;
            $result['recipient_name']   = $this->buyerFullName;
            $result['recipient_email']  = $this->buyerEmail;
            $result['sender_name']      = $adModel->customer->fullName;
            $result['sender_email']     = $adModel->customer->email;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return array
     */
    public function getVarsList()
    {
        return $this->varsList;
    }
}