<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0.1
 */

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class CustomerStoreSearch
 * @package app\models
 */
class CustomerStoreSearch extends CustomerStore
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'customer_id'], 'integer'],
            [['store_name', 'company_name', 'company_no', 'vat', 'status', 'source', 'created_at', 'updated_at'], 'safe'],
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
        $query = CustomerStore::find();

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


        // grid filtering conditions
        $query->andFilterWhere([
            'store_id' => $this->store_id,
            'updated_at' => $this->updated_at,
        ]);

        if(strlen($this->customer_id) >= 1){
            if(is_numeric($this->customer_id)){
                $query->andWhere(['customer_id'=>$this->customer_id]);
            } else {
                $query->joinWith(['customer'=>function($query){
                    $query->from(Customer::tableName().' c2');
                }], true, 'INNER JOIN')->andWhere(['like', 'c2.name', $this->customer_id]);
            }
        }

        $query->andFilterWhere(['like', 'store_name', $this->store_name])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'company_no', $this->company_no])
            ->andFilterWhere(['like', 'vat', $this->vat])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['=', 'status', $this->status]);
        return $dataProvider;
    }
}
