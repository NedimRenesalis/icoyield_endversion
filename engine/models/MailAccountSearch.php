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
use app\models\MailAccount;

/**
 * MailAccountSearch represents the model behind the search form about `app\models\MailAccount`.
 */
class MailAccountSearch extends MailAccount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'port', 'timeout'], 'integer'],
            [['account_name', 'hostname', 'username', 'password', 'encryption', 'from', 'reply_to', 'used_for', 'created_at', 'updated_at'], 'safe'],
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
        $query = MailAccount::find();

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
            'account_id' => $this->account_id,
            'port' => $this->port,
            'timeout' => $this->timeout,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'hostname', $this->hostname])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'encryption', $this->encryption])
            ->andFilterWhere(['like', 'from', $this->from])
            ->andFilterWhere(['like', 'reply_to', $this->reply_to])
            ->andFilterWhere(['like', 'used_for', $this->used_for]);

        return $dataProvider;
    }
}
