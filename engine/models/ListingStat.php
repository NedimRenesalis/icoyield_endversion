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

/**
 * Class ListingStat
 * @package app\models
 */
class ListingStat extends \app\models\auto\ListingStat
{
    CONST TOTAL_VIEWS = 'total_views';

    CONST FACEBOOK_SHARES = 'facebook_shares';

    CONST TWITTER_SHARES = 'twitter_shares';

    CONST MAIL_SHARES = 'mail_shares';

    CONST FAVORITE = 'favorite';

    CONST SHOW_PHONE = 'show_phone';

    CONST SHOW_MAIL = 'show_mail';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['listing_id'], 'required'],
            [['listing_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'total_views'       => t('app', 'Total Views'),
            'facebook_shares'   => t('app', 'Facebook Shares'),
            'twitter_shares'    => t('app', 'Twitter Shares'),
            'mail_shares'       => t('app', 'Mail Shares'),
            'favorite'          => t('app', 'Favorite'),
            'show_phone'        => t('app', 'Show Phone'),
            'show_mail'         => t('app', 'Show Mail'),
           ];
    }

    /**
     * @param $statsType
     * @param $adId
     */
    public static function track($statsType, $adId)
    {
        $availableStats = [
            self::TOTAL_VIEWS,
            self::FACEBOOK_SHARES,
            self::TWITTER_SHARES,
            self::MAIL_SHARES,
            self::FAVORITE,
            self::SHOW_PHONE,
            self::SHOW_MAIL,
        ];

        if(!in_array($statsType,$availableStats)){
            return;
        }

        $_stat = self::find()->where(['listing_id' => $adId])->one();
        if(empty($_stat)){
            $_stat = new self();
            $_value = 0;
        } else {
            $_value = $_stat->$statsType;
        }

        $_stat->listing_id = $adId;
        $_stat->$statsType = $_value + 1;
        $_stat->save(false);
    }
}