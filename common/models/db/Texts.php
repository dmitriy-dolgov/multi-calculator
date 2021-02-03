<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "texts".
 *
 * @property int $id
 * @property string|null $group Группа, к которой относится текст
 * @property string|null $type text, html и т. п.
 * @property string|null $data
 */
class Texts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'texts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['group'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group' => Yii::t('app', 'Group'),
            'type' => Yii::t('app', 'Type'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TextsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TextsQuery(get_called_class());
    }
}
