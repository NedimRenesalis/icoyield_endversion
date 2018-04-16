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

namespace app\components;

use yii\base\Widget;
use yii\base\InvalidConfigException;

class SocialMediaListWidget extends Widget
{
    /**
     * @var int number of items to retrieve
     */
    public $quantity = 10;

    /**
     * @var string title of list
     */
    public $title;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->title === null) {
            throw new InvalidConfigException('The "title" property must be set.');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $socialMediaLinks = [];
        // retrieve and fill list of socila media links where key is font awesome icon
        $socialMediaLinks['fa-facebook-square'] = options()->get('app.settings.social.facebook', '');
        $socialMediaLinks['fa-instagram'] = options()->get('app.settings.social.instagram', '');
        $socialMediaLinks['fa-twitter-square'] = options()->get('app.settings.social.twitter', '');
        $socialMediaLinks['fa-google-plus-square'] = options()->get('app.settings.social.googlePlus', '');
        $socialMediaLinks['fa-pinterest-square'] = options()->get('app.settings.social.pinterest', '');
        $socialMediaLinks['fa-linkedin-square'] = options()->get('app.settings.social.linkedin', '');
        $socialMediaLinks['fa-vk'] = options()->get('app.settings.social.vkontakte', '');

        return $this->render('social-media/social-media-list', [
            'socialMediaLinks' => $socialMediaLinks,
            'title'            => $this->title,
        ]);
    }
}