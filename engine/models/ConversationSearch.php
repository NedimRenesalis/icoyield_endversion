<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Conversation;

/**
 * ConversationSearch represents the model behind the search form about `app\models\Conversation`.
 */
class ConversationSearch extends Conversation
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conversation_id'], 'integer'],
            [['status', 'created_at', 'updated_at', 'listing_id', 'seller_id', 'buyer_id'], 'safe'],
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
        $query = Conversation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['updated_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (strlen($this->listing_id) >= 1) {
            $query->joinWith(['listing' => function ($query) {
                $query->from(Listing::tableName() . ' l');
            }], true, 'INNER JOIN')->andWhere(['like', 'l.title', $this->listing_id]);
        }
        if (strlen($this->seller_id) >= 1) {
            $query->joinWith(['seller' => function ($query) {
                $query->from(Customer::tableName() . ' c1');
            }], true, 'INNER JOIN')
                ->andWhere(['like', 'c1.first_name', $this->seller_id])
                ->orWhere(['like', 'c1.last_name', $this->seller_id]);
        }
        if (strlen($this->buyer_id) >= 1) {
            $query->joinWith(['buyer' => function ($query) {
                $query->from(Customer::tableName() . ' c2');
            }], true, 'INNER JOIN')
                ->andWhere(['like', 'c2.first_name', $this->buyer_id])
                ->orWhere(['like', 'c2.last_name', $this->buyer_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'conversation_id' => $this->conversation_id,
            'status'          => $this->status,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
