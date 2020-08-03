<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\CustomerActiveComponent;

/**
 * CustomerActiveComponentSearch represents the model behind the search form of `common\models\db\CustomerActiveComponent`.
 */
class CustomerActiveComponentSearch extends CustomerActiveComponent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'component_id', 'amount', 'unit_id'], 'integer'],
            [['price_override', 'price_discount_override', 'unit_value', 'unit_value_min', 'unit_value_max'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = CustomerActiveComponent::find();

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
            'id' => $this->id,
            'component_id' => $this->component_id,
            'price_override' => $this->price_override,
            'price_discount_override' => $this->price_discount_override,
            'amount' => $this->amount,
            'unit_id' => $this->unit_id,
            'unit_value' => $this->unit_value,
            'unit_value_min' => $this->unit_value_min,
            'unit_value_max' => $this->unit_value_max,
        ]);

        return $dataProvider;
    }
}
