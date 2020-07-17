<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "component_set".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property ComponentComponentSet[] $componentComponentSets
 * @property Component[] $components
 */
class ComponentSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'component_set';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Gets query for [[ComponentComponentSets]].
     *
     * @return \yii\db\ActiveQuery|ComponentComponentSetQuery
     */
    public function getComponentComponentSets()
    {
        return $this->hasMany(ComponentComponentSet::className(), ['component_set_id' => 'id']);
    }

    /**
     * Gets query for [[Components]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getComponents()
    {
        return $this->hasMany(Component::className(), ['id' => 'component_id'])->viaTable('component_component_set', ['component_set_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ComponentSetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComponentSetQuery(get_called_class());
    }
}
