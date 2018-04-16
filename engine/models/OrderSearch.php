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
 * Search represents the model behind the search form about `app\models\Category`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','customer_id','listing_id','country_id', 'zone_id'], 'integer'],
            [['first_name', 'last_name','company_name', 'company_no', 'vat', 'city', 'zip', 'phone', 'total', 'status', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the country_id class
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
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['=', 'order_id', $this->order_id])
            ->andFilterWhere(['like',   'first_name', $this->first_name])
            ->andFilterWhere(['like',   'last_name', $this->last_name])
            ->andFilterWhere(['like',   'company_name', $this->company_name])
            ->andFilterWhere(['like',   'total', $this->total])
            ->andFilterWhere(['=',      'status', $this->status])
            ->andFilterWhere(['like',   'created_at', $this->created_at])
            ->andFilterWhere(['=',      'updated_at', $this->updated_at]);
        return $dataProvider;
    }
}
