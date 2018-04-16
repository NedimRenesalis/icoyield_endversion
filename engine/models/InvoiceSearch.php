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
use app\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `app\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    const INVOICES_PER_PAGE = 10;

    public $firstName;
    public $lastName;
    public $company;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'order_id'], 'integer'],
            [['created_at', 'updated_at', 'firstName', 'lastName', 'company'], 'safe'],
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
        $query = Invoice::find()->alias( 'i' );

        // add conditions that should always apply here
        $query->joinWith(['order o']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'       => ['defaultOrder' => ['invoice_id' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => self::INVOICES_PER_PAGE,
            ],
        ]);

        $dataProvider->sort->attributes['firstName'] = [
            'asc' => ['o.first_name' => SORT_ASC],
            'desc' => ['o.first_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['lastName'] = [
            'asc' => ['o.last_name' => SORT_ASC],
            'desc' => ['o.last_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['company'] = [
            'asc' => ['o.company_name' => SORT_ASC],
            'desc' => ['o.company_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'i.invoice_id' => $this->invoice_id,
            'i.order_id'   => $this->order_id,
        ]);

        $query->andFilterWhere(['like', 'o.first_name', $this->firstName]);
        $query->andFilterWhere(['like', 'o.last_name', $this->lastName]);
        $query->andFilterWhere(['like', 'o.company_name', $this->company]);
        $query->andFilterWhere(['like', 'i.created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'i.updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
