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

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MailQueue;

/**
 * MailQueueSearch represents the model behind the search form about `app\models\MailQueue`.
 */
class MailQueueSearch extends MailQueue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'attempts'], 'integer'],
            [['subject', 'message_template_type', 'swift_message', 'last_attempt_time', 'time_to_send', 'sent_time', 'created_at'], 'safe'],
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
        $query = MailQueue::find();

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
            'id'        => $this->id,
            'attempts'  => $this->attempts,
            'sent_time' => $this->sent_time,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'swift_message', $this->swift_message])
            ->andFilterWhere(['=', 'message_template_type', $this->message_template_type])
            ->andFilterWhere(['like', 'last_attempt_time', $this->last_attempt_time])
            ->andFilterWhere(['like', 'time_to_send', $this->time_to_send])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);


        return $dataProvider;
    }
}
