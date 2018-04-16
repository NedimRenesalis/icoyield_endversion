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
class OrderTransactionSearch extends OrderTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['gateway', 'transaction_reference', 'type', 'gateway_response', 'created_at', 'updated_at'], 'safe'],
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
        $query = OrderTransaction::find();

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
            ->andFilterWhere(['=',   'transaction_reference', $this->transaction_reference])
            ->andFilterWhere(['=',   'gateway', $this->gateway])
            ->andFilterWhere(['like',   'type', $this->type])
            ->andFilterWhere(['like',   'created_at', $this->created_at])
            ->andFilterWhere(['=',      'updated_at', $this->updated_at]);
        return $dataProvider;
    }
}
