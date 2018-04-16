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

use app\models\Category;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class CategoriesListWidget extends Widget
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
        $categories = Category::find()
            ->where(['status' => Category::STATUS_ACTIVE])
            ->andWhere(['is', 'parent_id', null])
            ->limit($this->quantity)
            ->orderBy(['name' => SORT_ASC])
            ->all();

        return $this->render('category/categories-list', [
            'categories' => $categories,
            'title'      => $this->title,
        ]);
    }
}