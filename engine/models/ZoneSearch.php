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
class ZoneSearch extends Zone
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zone_id'], 'integer'],
            [['zone_id', 'name', 'code', 'country_id', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Zone::find()->from(static::tableName().' t');

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
            't.zone_id' => $this->zone_id,
            't.updated_at' => $this->updated_at,
        ]);

        if(strlen($this->country_id) >= 1){
            if(is_numeric($this->country_id)){
                $query->andWhere(['t.country_id'=>$this->country_id]);
            } else {
                $query->joinWith(['country'=>function($query){
                    $query->from(Country::tableName().' c2');
                }], true, 'INNER JOIN')->andWhere(['like', 'c2.name', $this->country_id]);
            }
        }

        $query->andFilterWhere(['like', 't.zone_id', $this->zone_id])
            ->andFilterWhere(['like', 't.name', $this->name])
            ->andFilterWhere(['like', 't.code', $this->code])
            ->andFilterWhere(['like', 't.created_at', $this->created_at])
            ->andFilterWhere(['=', 't.status', $this->status]);
        return $dataProvider;
    }
}
