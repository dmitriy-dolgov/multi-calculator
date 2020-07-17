<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\ShopOrderSignal;

/**
 * ShopOrderSignalSearch represents the model behind the search form of `common\models\db\ShopOrderSignal`.
 */
class ShopOrderSignalSearch extends ShopOrderSignal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['emails', 'phones'], 'safe'],
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
        $query = ShopOrderSignal::find();

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
        $query->andWhere([
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'emails', $this->emails])
            ->andFilterWhere(['like', 'phones', $this->phones]);

        return $dataProvider;
    }
}
