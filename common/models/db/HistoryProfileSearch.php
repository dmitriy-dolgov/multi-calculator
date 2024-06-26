<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\HistoryProfile;

/**
 * HistoryProfileSearch represents the model behind the search form of `common\models\db\HistoryProfile`.
 */
class HistoryProfileSearch extends HistoryProfile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'user_customer_id'], 'integer'],
            [['name', 'about', 'server_info', 'remote_info', 'created_at', 'changed_at', 'deleted_at'], 'safe'],
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
        $query = HistoryProfile::find();

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
            'user_id' => $this->user_id,
            'user_customer_id' => $this->user_customer_id,
            'created_at' => $this->created_at,
            'changed_at' => $this->changed_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'about', $this->about])
            ->andFilterWhere(['like', 'server_info', $this->server_info])
            ->andFilterWhere(['like', 'remote_info', $this->remote_info]);

        return $dataProvider;
    }
}
