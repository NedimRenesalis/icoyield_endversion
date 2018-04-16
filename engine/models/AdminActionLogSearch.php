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
use app\models\AdminActionLog;

/**
 * AdminActionLogSearch represents the model behind the search form about `app\models\AdminActionLog`.
 */
class AdminActionLogSearch extends AdminActionLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_id', 'changed_by'], 'integer'],
            [['controller_name', 'action_name', 'changed_model', 'changed_data', 'element', 'created_at'], 'safe'],
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
        $query = AdminActionLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['action_id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'action_id'  => $this->action_id,
            'changed_by' => $this->changed_by,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'controller_name', $this->controller_name])
            ->andFilterWhere(['like', 'action_name', $this->action_name])
            ->andFilterWhere(['like', 'changed_model', $this->changed_model])
            ->andFilterWhere(['like', 'changed_data', $this->changed_data])
            ->andFilterWhere(['like', 'element', $this->element]);

        return $dataProvider;
    }
}
