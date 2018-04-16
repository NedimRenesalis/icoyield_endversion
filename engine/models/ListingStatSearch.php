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
use yii\data\ActiveDataProvider;

/**
 * Class ListingStatSearch
 * @package app\models
 */
class ListingStatSearch extends ListingStat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['listing_id'], 'integer'],
            [['total_views','facebook_shares','twitter_shares','mail_shares', 'favorite', 'show_phone', 'show_mail', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ListingStat::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['=', 'listing_id', $this->listing_id])
            ->andFilterWhere(['=', 'total_views', $this->total_views])
            ->andFilterWhere(['=', 'facebook_shares', $this->facebook_shares])
            ->andFilterWhere(['=', 'twitter_shares', $this->twitter_shares])
            ->andFilterWhere(['=', 'mail_shares', $this->mail_shares])
            ->andFilterWhere(['=', 'favorite', $this->favorite])
            ->andFilterWhere(['=', 'show_phone', $this->show_phone])
            ->andFilterWhere(['=', 'show_mail', $this->show_mail])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);
        return $dataProvider;
    }
}
