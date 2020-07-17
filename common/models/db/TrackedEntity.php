<?php

namespace common\models\db;

class TrackedEntity extends \yii\db\ActiveQuery
{
    public function prepare($builder)
    {
        $this->andWhere(['deleted_at' => null]);

        /*if (isset($this->user_id)) {
            $this->andWhere(['user_id' => \Yii::$app->user->getId()]);
        }*/

        return parent::prepare($builder);
    }

    /*public function behaviors()
    {
        return [
            'softDelete' => [
                'class' => SoftDeleteQueryBehavior::className(),
                'deletedCondition' => [
                    'deleted_at' => null,
                ],
                'notDeletedCondition' => [
                    'deleted_at' => true,
                ],
            ],
        ];
    }*/

    public function behaviors()
    {
        return [
            [
                'class' => \cornernote\softdelete\SoftDeleteQueryBehavior::className(),
                'attribute' => 'deleted_at',
            ],
        ];
    }
}
