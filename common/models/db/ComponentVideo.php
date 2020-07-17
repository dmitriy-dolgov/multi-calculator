<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "component_video".
 *
 * @property int $id
 * @property string|null $relative_path
 * @property int|null $component_id
 * @property string|null $mime_type
 * @property string|null $deleted_at
 *
 * @property Component $component
 */
class ComponentVideo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'component_video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['component_id'], 'integer'],
            [['deleted_at'], 'safe'],
            [['relative_path', 'mime_type'], 'string', 'max' => 255],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Component::className(), 'targetAttribute' => ['component_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'relative_path' => Yii::t('app', 'Relative Path'),
            'component_id' => Yii::t('app', 'Component ID'),
            'mime_type' => Yii::t('app', 'Mime Type'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * Gets query for [[Component]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getComponent()
    {
        return $this->hasOne(Component::className(), ['id' => 'component_id']);
    }

    /**
     * {@inheritdoc}
     * @return ComponentVideoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComponentVideoQuery(get_called_class());
    }
}
