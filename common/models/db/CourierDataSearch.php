<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\CourierData;

/**
 * CourierDataSearch represents the model behind the search form of `common\models\db\CourierData`.
 */
class CourierDataSearch extends CourierData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'old_user_id', 'new_user_id'], 'integer'],
            [['name', 'description'], 'safe'],
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
        $query = CourierData::find();

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
            'old_user_id' => $this->old_user_id,
            'new_user_id' => $this->new_user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
