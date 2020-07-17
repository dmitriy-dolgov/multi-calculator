<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_name
 * @property string|null $symbol
 * @property string|null $symbol_pattern Шаблон, демонстрирующий значение вместе со знаком единицы
 *
 * @property Component[] $components
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['short_name'], 'string', 'max' => 20],
            [['symbol'], 'string', 'max' => 10],
            [['symbol_pattern'], 'string', 'max' => 40],
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
            'short_name' => Yii::t('app', 'Short Name'),
            'symbol' => Yii::t('app', 'Symbol'),
            'symbol_pattern' => Yii::t('app', 'Symbol Pattern'),
        ];
    }

    /**
     * Gets query for [[Components]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getComponents()
    {
        return $this->hasMany(Component::className(), ['unit_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UnitQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UnitQuery(get_called_class());
    }
}
