<?php

namespace common\models\db;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\ShopOrder;

/**
 * ShopOrderSearch represents the model behind the search form of `common\models\db\ShopOrder`.
 */
class ShopOrderSearch extends ShopOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['order_uid', 'created_at', 'deliver_address', 'deliver_customer_name', 'deliver_phone', 'deliver_email', 'deliver_comment', 'deliver_required_time_begin', 'deliver_required_time_end'], 'safe'],
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
        $query = ShopOrder::find();

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

        /*$query->andWhere([
            'shopOrderUsers' => $this->user_id,
            'user_id' => $this->user_id,
        ]);*/
        /*$query->andWhere([
            'user_id' => $this->user_id,
        ]);*/

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'deliver_required_time_begin' => $this->deliver_required_time_begin,
            'deliver_required_time_end' => $this->deliver_required_time_end,
        ]);

        $query->andFilterWhere(['like', 'order_uid', $this->order_uid])
            ->andFilterWhere(['like', 'deliver_address', $this->deliver_address])
            ->andFilterWhere(['like', 'deliver_customer_name', $this->deliver_customer_name])
            ->andFilterWhere(['like', 'deliver_phone', $this->deliver_phone])
            ->andFilterWhere(['like', 'deliver_email', $this->deliver_email])
            ->andFilterWhere(['like', 'deliver_comment', $this->deliver_comment]);

        return $dataProvider;
    }
}
