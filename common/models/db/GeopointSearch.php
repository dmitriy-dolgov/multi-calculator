<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\Geopoint;

/**
 * GeopointSearch represents the model behind the search form of `common\models\db\Geopoint`.
 */
class GeopointSearch extends Geopoint
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'region', 'sub_region', 'code_cdek', 'kladr_code', 'uuid', 'fias_uuid', 'country', 'region_code', 'lat_long', 'index', 'code_boxberry', 'code_dpd'], 'safe'],
            [['merchant_coverage_radius'], 'number'],
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
        $query = Geopoint::find();

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
            'merchant_coverage_radius' => $this->merchant_coverage_radius,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'region', $this->region])
            ->andFilterWhere(['like', 'sub_region', $this->sub_region])
            ->andFilterWhere(['like', 'code_cdek', $this->code_cdek])
            ->andFilterWhere(['like', 'kladr_code', $this->kladr_code])
            ->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'fias_uuid', $this->fias_uuid])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'region_code', $this->region_code])
            ->andFilterWhere(['like', 'lat_long', $this->lat_long])
            ->andFilterWhere(['like', 'index', $this->index])
            ->andFilterWhere(['like', 'code_boxberry', $this->code_boxberry])
            ->andFilterWhere(['like', 'code_dpd', $this->code_dpd]);

        return $dataProvider;
    }
}
