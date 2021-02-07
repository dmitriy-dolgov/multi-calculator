<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "texts".
 *
 * @property string $id
 * @property string|null $group Группа, к которой относится текст (договор, страница и т.п.)
 * @property string|null $type text, html, mime-type и т.п.
 * @property string|null $description
 * @property string|null $content
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
            [['id'], 'required'],
            [['description', 'content'], 'string'],
            [['id'], 'string', 'max' => 100],
            [['group'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group' => Yii::t('app_c', 'Group'),
            'type' => Yii::t('app_c', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
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
