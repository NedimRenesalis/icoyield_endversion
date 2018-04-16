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

namespace app\helpers;

/**
 * Class LanguageHelper
 * @package app\helpers
 */
class LanguageHelper
{
    /**
     * @param null $language
     * @return string
     */
    public static function getLanguageOrientation($language = null)
    {
        if($language === null){
            $language = app()->language;
        }
        $language = explode('-',$language)[0];

        $rtl =  array("ar", "ar_001", "ar_ae", "ar_bh", "ar_dj", "ar_dz", "ar_eg", "ar_eh", "ar_er", "ar_il", "ar_iq", "ar_jo", "ar_km", "ar_kw", "ar_lb", "ar_ly", "ar_ma", "ar_mr", "ar_om", "ar_ps", "ar_qa", "ar_sa", "ar_sd", "ar_so", "ar_sy", "ar_td", "ar_tn", "ar_ye", "dv", "dv_mv", "fa", "fa_af", "fa_ir", "ha_arab", "ha_arab_ng", "ha_arab_sd", "he", "he_il", "ks", "ks_arab", "ks_arab_in", "ku", "ku_arab", "ku_arab_iq", "ku_arab_ir", "ku_iq", "ku_ir", "ku_latn", "ku_latn_sy", "ku_latn_tr", "ku_sy", "ku_tr", "pa_arab", "pa_arab_pk", "ps", "ps_af", "syr", "syr_sy", "ug_cn", "ur", "ur_in", "ur_pk", "uz_arab", "uz_arab_af");

        return !in_array($language,$rtl) ? 'ltr' : 'rtl';
    }
}