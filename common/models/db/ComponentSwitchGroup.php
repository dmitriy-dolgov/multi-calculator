<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "component_switch_group".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $description
 *
 * @property Component[] $components
 * @property User $user
 */
class ComponentSwitchGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'component_switch_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[Components]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getComponents()
    {
        return $this->hasMany(Component::className(), ['unit_switch_group' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|Da\User\Query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return ComponentSwitchGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComponentSwitchGroupQuery(get_called_class());
    }
}
