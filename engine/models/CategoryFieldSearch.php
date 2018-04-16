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
 * CategoryFieldSearch represents the model behind the search form about `app\models\CategoryField`.
 */
class CategoryFieldSearch extends CategoryField
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'created_at', 'type_id', 'sort_order', 'label', 'required'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = CategoryField::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if(strlen($this->type_id) >= 1){
            if(is_numeric($this->type_id)){
                $query->andWhere(['type_id'=>$this->type_id]);
            } else {
                $query->joinWith(['type'=>function($query){
                    $query->from(CategoryFieldType::tableName().' c2');
                }], true, 'INNER JOIN')->andWhere(['like', 'c2.name', $this->type_id]);
            }
        }

        $query->andFilterWhere(['like', 'field_id', $this->field_id])
            ->andFilterWhere(['=', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'required', $this->required])
            ->andFilterWhere(['like', 'sort_order', $this->sort_order])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);
        return $dataProvider;
    }
}
