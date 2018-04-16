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
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;


/**
 * Search represents the model behind the search form about `app\models\Category`.
 */
class TaxSearch extends Tax
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tax_id'], 'integer'],
            [['country_id', 'zone_id', 'is_global', 'percent', 'name', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Tax::find()->from(static::tableName().' t');

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
            't.tax_id' => $this->tax_id,
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

        if(strlen($this->zone_id) >= 1){
            if(is_numeric($this->zone_id)){
                $query->andWhere(['t.zone_id'=>$this->zone_id]);
            } else {
                $query->joinWith(['zone'=>function($query){
                    $query->from(Zone::tableName().' c2');
                }], true, 'INNER JOIN')->andWhere(['like', 'c2.name', $this->zone_id]);
            }
        }

        $query->andFilterWhere(['like', 't.tax_id', $this->tax_id])
            ->andFilterWhere(['like', 't.name', $this->name])
            ->andFilterWhere(['like', 't.percent', $this->percent])
            ->andFilterWhere(['=', 't.is_global', $this->is_global])
            ->andFilterWhere(['like', 't.created_at', $this->created_at])
            ->andFilterWhere(['=', 't.status', $this->status]);
        return $dataProvider;
    }
}
