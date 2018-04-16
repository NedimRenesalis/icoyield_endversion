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
 * CustomerSearch represents the model behind the search form about `app\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['customer_uid', 'group_id', 'first_name', 'last_name', 'email', 'password', 'avatar', 'status', 'source','adsCount', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = Customer::find()->alias('t');
        $select = ['*', '(SELECT COUNT(*) FROM {{%listing}} WHERE customer_id = t.customer_id) AS adsCount'];

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['first_name', 'last_name', 'email', 'source', 'created_at', 'status', 'adsCount']
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'customer_id' => $this->customer_id,
            'updated_at' => $this->updated_at,
        ]);

        if($this->getAdsCount() !== null){
            $query->andWhere(['(SELECT COUNT(*) FROM {{%listing}} WHERE customer_id = t.customer_id)'=>$this->getAdsCount()]);
        }
        $query->select($select);
        $query->andFilterWhere(['like', 'customer_uid', $this->customer_uid])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['=', 'status', $this->status]);
        return $dataProvider;
    }
}
