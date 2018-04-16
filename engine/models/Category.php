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

use app\helpers\CommonHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * Class Category
 * @package app\models
 */
class Category extends \app\models\auto\Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort_order', 'slug'], 'required'],
            [['name'], 'unique', 'targetAttribute' => 'parent_id'],
            ['slug', 'unique'],
            [['status'], 'string'],
            [['sort_order', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['description', 'icon'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'parent_id'     => t('app', 'Parent Name'),
            'name'          => t('app','Category Name'),
            'parent.name'   => t('app', 'Parent Name'),
            'status'        => t('app', 'Status'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(static::className(), ['category_id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(static::className(), ['parent_id' => 'category_id']);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $name = '';
        if ($parent = $this->parent) {
            $name .= $parent->getFullName() . ' -> ';
        }

        return $name . $this->name;
    }

    /**
     * @param null $currentCategoryId
     * @return array
     */
    public static function getAllCategories($currentCategoryId = null)
    {
        $models = static::find()
            ->orderBy(['parent_id' => SORT_ASC, 'category_id' => SORT_ASC])
            ->all();
        $categories = [];
        foreach ($models as $model) {
            $categories[$model->category_id] = $model->getFullName();
        }
        if (isset($categories[$currentCategoryId]) && $currentCategoryId != 0) {
            unset($categories[$currentCategoryId]);
        }

        return $categories;
    }

    /**
     * Retrieve and generate list of categories where item looks like 'slug' => 'fullName'
     *
     * @return array
     */
    public static function getCategoriesListBySlug()
    {
        $models = static::find()
            ->orderBy(['parent_id' => SORT_ASC, 'category_id' => SORT_ASC])
            ->all();
        $categories = [];
        foreach ($models as $model) {
            $categories[$model->slug] = $model->getFullName();
        }

        return $categories;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        $path = '';
        if ($name = $this->parent) {
            $path .= $name->getPath() . '/';
        }

        return $path . Inflector::slug($this->name);
    }

    /**
     * @param null $categoryId
     * @return array
     */
    public static function getAllParents($categoryId = null)
    {
        $query = static::find();
        $query->where(['category_id' => $categoryId]);
        $models = $query->all();
        $parents = [];
        foreach ($models as $key => $model) {
            $children = static::getAllParents($model->parent_id);
            $parents[$key]['name'] = $model->name;
            $parents[$key]['slug'] = $model->slug;
            $parents[$key]['path'] = $model->getPath();
            $parents = ArrayHelper::merge($parents, $children);
        }

        return $parents;
    }

    /**
     * @return auto\Category[]|array
     */
    public static function getTopCatsWithFirstLevelChildren()
    {
        return static::find()->with([
            'children' => function (\yii\db\ActiveQuery $query) {
                $query->orderBy(['sort_order' => 'ASC']);
            },
        ])->where(['is', 'parent_id', null])->orderBy(['sort_order' => 'ASC'])->all();
    }

    /**
     * Get list of categories starting from parent until to the last child in a hierarchy
     *
     * @param            $slug
     * @param bool|false $isIdsList
     *
     * @return array
     */
    public static function getHierarchyOfCategoriesBySlug($slug, $isIdsList = false)
    {
        $connection = app()->getDb();
        $command = $connection->createCommand("
            SELECT category_id, name, parent_id
            FROM (SELECT * FROM " . self::tableName() . "
              ORDER BY parent_id, category_id) category_sorted,
              (SELECT @pv := (SELECT category_id FROM " . self::tableName() . " WHERE slug = :slug)) initialisation
            WHERE (find_in_set(parent_id, @pv) > 0 AND @pv := concat(@pv, ',', category_id)) OR slug = :slug
        ", [
            ':slug' => $slug
        ]);

        $list = $command->queryAll();

        if ($isIdsList) {
            $list = CommonHelper::ArrayColumn($list, 'category_id');
        }

        return $list;
    }

    /**
     * @param null $category
     * @return string
     */
    public static function getMetaDescription($category = null)
    {
        return StringHelper::truncate(strip_tags($category->description), 160, '');
    }

    /**
     * @param $slug
     * @return static
     */
    public static function findCategoryBySlug($slug)
    {
        return static::findOne(['slug' => $slug]);
    }
}
