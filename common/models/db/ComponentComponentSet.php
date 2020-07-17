<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "component_component_set".
 *
 * @property int $component_id
 * @property int $component_set_id
 *
 * @property Component $component
 * @property ComponentSet $componentSet
 */
class ComponentComponentSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'component_component_set';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['component_id', 'component_set_id'], 'required'],
            [['component_id', 'component_set_id'], 'integer'],
            [['component_id', 'component_set_id'], 'unique', 'targetAttribute' => ['component_id', 'component_set_id']],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Component::className(), 'targetAttribute' => ['component_id' => 'id']],
            [['component_set_id'], 'exist', 'skipOnError' => true, 'targetClass' => ComponentSet::className(), 'targetAttribute' => ['component_set_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'component_id' => Yii::t('app', 'Component ID'),
            'component_set_id' => Yii::t('app', 'Component Set ID'),
        ];
    }

    /**
     * Gets query for [[Component]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComponent()
    {
        return $this->hasOne(Component::className(), ['id' => 'component_id']);
    }

    /**
     * Gets query for [[ComponentSet]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComponentSet()
    {
        return $this->hasOne(ComponentSet::className(), ['id' => 'component_set_id']);
    }
}
