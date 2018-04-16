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
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['user_uid', 'group_id', 'first_name', 'last_name', 'email', 'password', 'timezone', 'avatar', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = User::find();

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
            'user_id' => $this->user_id,
            'updated_at' => $this->updated_at,
        ]);

        if(strlen($this->group_id) >= 1){
            if(is_numeric($this->group_id)){
                $query->andWhere(['group_id'=>$this->group_id]);
            } else {
                $query->joinWith(['group'=>function($query){
                    $query->from(Group::tableName().' c2');
                }], true, 'INNER JOIN')->andWhere(['like', 'c2.name', $this->group_id]);
            }
        }

        $query->andFilterWhere(['like', 'user_uid', $this->user_uid])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'timezone', $this->timezone])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['=', 'status', $this->status]);
        return $dataProvider;
    }
}
