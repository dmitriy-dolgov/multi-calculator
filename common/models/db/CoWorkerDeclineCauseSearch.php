<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\CoWorkerDeclineCause;

/**
 * CoWorkerDeclineCauseSearch represents the model behind the search form of `common\models\db\CoWorkerDeclineCause`.
 */
class CoWorkerDeclineCauseSearch extends CoWorkerDeclineCause
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'co_worker_id', 'order'], 'integer'],
            [['cause'], 'safe'],
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
        $query = CoWorkerDeclineCause::find();

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
            'co_worker_id' => $this->co_worker_id,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'cause', $this->cause]);

        return $dataProvider;
    }
}
