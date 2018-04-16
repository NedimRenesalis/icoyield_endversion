<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConversationMessage;

/**
 * ConversationMessageSearch represents the model behind the search form about `app\models\ConversationMessage`.
 */
class ConversationMessageSearch extends ConversationMessage
{
    public $customer;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conversation_message_id', 'conversation_id', 'seller_id', 'buyer_id', 'is_read'], 'integer'],
            [['message', 'customer', 'created_at', 'updated_at'], 'safe'],
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
        $query = ConversationMessage::find();

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

        // filter by customer
        if ($this->customer) {
            $query->andFilterWhere([
                'or',
                ['=', 'seller_id', $this->customer],
                ['=', 'buyer_id', $this->customer],
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'conversation_message_id' => $this->conversation_message_id,
            'conversation_id' => $this->conversation_id,
            'seller_id' => $this->seller_id,
            'buyer_id' => $this->buyer_id,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
