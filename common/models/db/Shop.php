<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "shop".
 *
 * @property int $id
 * @property string $name
 * @property int $location_id
 * @property int $top_category_id Указывает на коренную категорию через которую идет привязка более низовых категорий и компонентов
 * @property string $deleted_at
 *
 * @property Location $location
 * @property Category $topCategory
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'top_category_id'], 'integer'],
            [['deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']],
            [['top_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['top_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'location_id' => 'Location ID',
            'top_category_id' => 'Top Category ID',
            'deleted_at' => 'Deleted at',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'top_category_id']);
    }

    /**
     * {@inheritdoc}
     * @return ShopQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopQuery(get_called_class());
    }
}
