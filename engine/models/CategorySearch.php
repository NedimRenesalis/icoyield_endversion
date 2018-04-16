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
class CategorySearch extends Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['category_id', 'name', 'parent_id', 'status', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent_id class
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
        $query = Category::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        /**
         * Sort manually if no sort provided
         */
        $query->orderBy(['parent_id'=> SORT_ASC,]);


        // grid filtering conditions
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'updated_at' => $this->updated_at,
        ]);

        if(strlen($this->parent_id) >= 1){
            if(is_numeric($this->parent_id)){
                $query->andWhere(['parent_id'=>$this->parent_id]);
            } else {
                $query->joinWith(['parent'=>function($query){
                    $query->from(static::tableName().' c2');
                }], true, 'INNER JOIN')->andWhere(['like', 'c2.name', $this->parent_id]);
            }
        }

        $query->andFilterWhere(['like', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['=', 'status', $this->status]);
        return $dataProvider;
    }
}
