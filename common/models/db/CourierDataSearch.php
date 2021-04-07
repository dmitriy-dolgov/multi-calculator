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
            [['id', 'velocity', 'priority'], 'integer'],
            [['name_of_courier', 'description_of_courier', 'photo_of_courier', 'courier_in_move', 'courier_is_waiting'], 'safe'],
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
            'velocity' => $this->velocity,
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['like', 'name_of_courier', $this->name_of_courier])
            ->andFilterWhere(['like', 'description_of_courier', $this->description_of_courier])
            ->andFilterWhere(['like', 'photo_of_courier', $this->photo_of_courier])
            ->andFilterWhere(['like', 'courier_in_move', $this->courier_in_move])
            ->andFilterWhere(['like', 'courier_is_waiting', $this->courier_is_waiting]);

        return $dataProvider;
    }
}
