<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\Location;

/**
 * LocationSearch represents the model behind the search form of `common\models\db\Location`.
 */
class LocationSearch extends Location
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['region', 'region_id', 'district', 'district_id', 'city', 'city_id', 'street', 'street_id', 'building', 'building_id', 'appartment', 'zip_code', 'arbitrary_address', 'deleted_at'], 'safe'],
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
        $query = Location::find();

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
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'region', $this->region])
            ->andFilterWhere(['like', 'region_id', $this->region_id])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'district_id', $this->district_id])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'city_id', $this->city_id])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'street_id', $this->street_id])
            ->andFilterWhere(['like', 'building', $this->building])
            ->andFilterWhere(['like', 'building_id', $this->building_id])
            ->andFilterWhere(['like', 'appartment', $this->appartment])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'arbitrary_address', $this->arbitrary_address]);

        return $dataProvider;
    }
}
