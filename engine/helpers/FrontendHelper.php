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

use app\models\Category;

/**
 * Class FrontendHelper
 * @package app\helpers
 */
class FrontendHelper
{
    /**
     * @return array|mixed
     */
    public static function getIcons()
    {
        $assetBundle = app()->assetManager->getBundle('rmrevin\yii\fontawesome\cdn\AssetBundle');
        $contentFile = $assetBundle->css;
        $contentFile = str_replace('.min','',$contentFile[0]);
        $response = CommonHelper::simpleCurlGet($contentFile);
        $output_array = [];
        if($response['status'] == 'success') {
            $pattern = "/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*\"\\\\(.+)\";\s+}/";
            if( preg_match_all($pattern, $response['message'], $output_array) ) {
                $output_array = $output_array[1];
            }
        }
        return $output_array;
    }

    /**
     * @param array $categories
     * @return array
     */
    public static function getCategoriesSorted($categories = [])
    {
        if(empty($categories)) return [];
        $sortedCategories = [];
        foreach ($categories as $category){
            if(!empty($category->parent_id)){
                $parent = Category::find()->where('category_id = '.$category->parent_id)->one();
                $sortedCategories[$category->parent_id]['name'] = $parent->name;
                $sortedCategories[$category->parent_id]['slug'] = $parent->slug;
                $sortedCategories[$category->parent_id]['children'][] = $category;
            }
        }
        return $sortedCategories;
    }
}
