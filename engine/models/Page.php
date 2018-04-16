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

use yii\helpers\ArrayHelper;

/**
 * Class Page
 * @package app\models
 */
class Page extends \app\models\auto\Page
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // identifiers of sections
    const SECTION_ONE = 1;
    const SECTION_TWO = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'content'], 'required'],
            [['content'], 'string'],
            [['section', 'sort_order'], 'integer'],
            [['title'], 'string', 'max' => 80],
            [['keywords'], 'string', 'max' => 160],
            [['description'], 'string', 'max' => 255],
            [['status'], 'safe'],
            [['slug'], 'unique'],
            ['slug', 'match', 'pattern' => '/^[a-z0-9-]+$/'],
            ['sort_order', 'required', 'when' => function ($model) {
                return $model->section != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#page-section').val() !== '';
            }"],
            [['section', 'sort_order'], 'unique', 'targetAttribute' => ['section', 'sort_order'], 'message' => 'The combination of Section and Sort Order has already been taken.'],
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'title'         => t('app', 'Page title'),
            'slug'          => t('app', 'Page slug'),
            'keywords'      => t('app', 'Page keywords'),
            'description'   => t('app', 'Page description'),
            'content'       => t('app', 'Page content'),
            'section'       => t('app', 'Section'),
            'sort_order'    => t('app', 'Sort Order'),
            'status'        => t('app', 'Status'),
            'created_at'    => t('app', 'Created At'),
        ]);
    }

    /**
     * Return list of available statuses of page
     *
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE   => self::STATUS_ACTIVE,
            self::STATUS_INACTIVE => self::STATUS_INACTIVE
        ];
    }

    /**
     * Return list of available sections, could be filtered by section id
     *
     * @param null $filter
     *
     * @return array|string
     */
    public static function getSectionsList($filter = null)
    {
        $sections = [
            self::SECTION_ONE => options()->get('app.settings.common.footerFirstPageSectionTitle', 'Help'),
            self::SECTION_TWO => options()->get('app.settings.common.footerSecondPageSectionTitle', 'About'),
        ];

        return $filter ? $sections[$filter] : $sections;
    }

    /**
     * Get name of section
     *
     * @return mixed
     */
    public function getSection()
    {
        $sections = $this->getSectionsList();

        return $this->section ? $sections[$this->section] : '';
    }

    /**
     * Get list of available position for sort_order
     *
     * @param      $sectionId
     *
     * @return array
     */
    public function getListOfAvailablePositions($sectionId)
    {
        $query = self::find()
            ->select('sort_order')
            ->where(['section' => $sectionId])
            ->indexBy('sort_order');

        if ($this->sort_order && ($this->section == $sectionId)) {
            $query->andWhere(['<>', 'sort_order', $this->sort_order]);
        }

        $occupiedPositions = $query->column();

        $available = array_diff(range(-30, 30), array_values($occupiedPositions));
        // copy values to keys
        $availablePositions = array_combine($available, $available);

        return $availablePositions;
    }
}
