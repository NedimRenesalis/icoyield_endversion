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

class TemplateTypeAd implements TemplateTypeInterface
{
    const TEMPLATE_TYPE = 1;

    /**
     * @var array list of variables of template
     */
    protected $varsList = [
        'customer_name'         => 'Customer Full Name',
        'customer_first_name'   => 'Customer First Name',
        'customer_last_name'    => 'Customer Last Name',
        'customer_email'        => 'Customer Email',
        'ad_title'              => 'Ad title',
        'ad_url'                => 'Ad URL',
        'general_site_name'     => 'General site name',
        'general_contact_email' => 'General contact email',
    ];

    protected $adId;

    public function __construct(array $data)
    {
        if (!empty($data)) {
            $this->adId = $data['listing_id'];
        }
    }

    /**
     * @inheritdoc
     */
    public function populate()
    {
        $adModel = Listing::find()->with('customer')->where(['listing_id' => $this->adId])->one();

        $this->recipient = $adModel->customer->email;

        return [
            'customer_first_name'   => $adModel->customer->first_name,
            'customer_last_name'    => $adModel->customer->last_name,
            'customer_name'         => $adModel->customer->fullName,
            'customer_email'        => $adModel->customer->email,
            'ad_title'              => $adModel->title,
            'ad_url'                => url(['/listing/index/', 'slug' => $adModel->slug], true),
            'general_site_name'     => options()->get('app.settings.common.siteName', 'EasyAds'),
            'general_contact_email' => options()->get('app.settings.common.siteEmail', 'contact@easyads.io'),
        ];
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