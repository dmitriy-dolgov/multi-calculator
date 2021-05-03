<?php

namespace common\models\db;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "component".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $short_name Короткая версия названия
 * @property int|null $parent_component_id Родительский элемент, если есть - значит этот компонент может являться частью другого компонента
 * @property int|null $category_id
 * @property string|null $deleted_at
 * @property string|null $description
 * @property string|null $short_description
 * @property float|null $price
 * @property float|null $price_discount
 * @property int $amount
 * @property int|null $item_select_min Мин. количество выбираемых компонентов
 * @property int|null $item_select_max Макс. количество выбираемых компонентов
 * @property int|null $unit_id
 * @property float|null $unit_value
 * @property float|null $unit_value_min
 * @property float|null $unit_value_max
 * @property int|null $unit_switch_group
 * @property int|null $disabled
 *
 * @property Category $category
 * @property Component $parentComponent
 * @property Component[] $components
 * @property Unit $unit
 * @property ComponentSwitchGroup $unitSwitchGroup
 * @property User $user
 * @property ComponentComponentSet[] $componentComponentSets
 * @property ComponentSet[] $componentSets
 * @property ComponentImage[] $componentImages
 * @property ComponentVideo[] $componentVideos
 * @property CustomerActiveComponent[] $customerActiveComponents
 * @property ShopOrderComponents[] $shopOrderComponents
 */
class Component extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'component';
    }

    public function behaviors()
    {
        return [
            //TODO: удалить из контроллера линк
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                //TODO: возможно, добавить поле чтобы знать кто изменял в последний раз
                'updatedByAttribute' => false,
            ],
            [
                'class' => \cornernote\softdelete\SoftDeleteBehavior::className(),
                'attribute' => 'deleted_at',
                'value' => new \yii\db\Expression('NOW()'), // for sqlite use - new \yii\db\Expression("date('now')")
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_component_id', 'category_id'], 'integer'],
            [['user_id', 'parent_component_id', 'category_id', 'amount', 'item_select_min', 'item_select_max', 'unit_id', 'unit_switch_group', 'disabled'], 'integer'],
            [['amount'], 'default', 'value' => 1],
            [['deleted_at'], 'safe'],
            [['description', 'short_description'], 'string'],
            [['price', 'price_discount', 'unit_value', 'unit_value_min', 'unit_value_max'], 'number'],
            [['name', 'short_name'], 'string', 'max' => 255],
            [['name', 'short_name'], 'required'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['parent_component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Component::className(), 'targetAttribute' => ['parent_component_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::className(), 'targetAttribute' => ['unit_id' => 'id']],
            [['unit_switch_group'], 'exist', 'skipOnError' => true, 'targetClass' => ComponentSwitchGroup::className(), 'targetAttribute' => ['unit_switch_group' => 'id']],
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
            'short_name' => Yii::t('app', 'Short name'),
            'parent_component_id' => Yii::t('app', 'Parent element ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'deleted_at' => Yii::t('app', 'Deleted at'),
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short description'),
            'price' => Yii::t('app', 'Price'),
            'price_discount' => Yii::t('app', 'Price without discount'),
            'amount' => Yii::t('app', 'Amount'),
            'item_select_min' => Yii::t('app', 'Item Select Min'),
            'item_select_max' => Yii::t('app', 'Item Select Max'),
            'unit_id' => Yii::t('app', 'Unit ID'),
            'unit_value' => Yii::t('app', 'Unit Value'),
            'unit_value_min' => Yii::t('app', 'Unit Value Min'),
            'unit_value_max' => Yii::t('app', 'Unit Value Max'),
            'unit_switch_group' => Yii::t('app', 'Unit Switch Group'),
            'disabled' => Yii::t('app', 'Disabled'),

            'category' => Yii::t('app', 'Category'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[ParentComponent]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getParentComponent()
    {
        return $this->hasOne(Component::className(), ['id' => 'parent_component_id']);
    }

    /**
     * Gets query for [[Components]].
     *
     * @return \yii\db\ActiveQuery|ComponentQuery
     */
    public function getComponents()
    {
        return $this->hasMany(Component::className(), ['parent_component_id' => 'id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery|UnitQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * Gets query for [[UnitSwitchGroup]].
     *
     * @return \yii\db\ActiveQuery|ComponentSwitchGroupQuery
     */
    public function getUnitSwitchGroup()
    {
        return $this->hasOne(ComponentSwitchGroup::className(), ['id' => 'unit_switch_group']);
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
     * Gets query for [[ComponentComponentSets]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getComponentComponentSets()
    {
        return $this->hasMany(ComponentComponentSet::className(), ['component_id' => 'id']);
    }

    /**
     * Gets query for [[ComponentSets]].
     *
     * @return \yii\db\ActiveQuery|ComponentSetQuery
     */
    public function getComponentSets()
    {
        return $this->hasMany(ComponentSet::className(), ['id' => 'component_set_id'])->viaTable('component_component_set', ['component_id' => 'id']);
    }

    /**
     * Gets query for [[ComponentImages]].
     *
     * @return \yii\db\ActiveQuery|ComponentImageQuery
     */
    public function getComponentImages()
    {
        return $this->hasMany(ComponentImage::className(), ['component_id' => 'id']);
    }

    /**
     * Gets query for [[ComponentVideos]].
     *
     * @return \yii\db\ActiveQuery|ComponentVideoQuery
     */
    public function getComponentVideos()
    {
        return $this->hasMany(ComponentVideo::className(), ['component_id' => 'id']);
    }

    /**
     * Gets query for [[CustomerActiveComponents]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getCustomerActiveComponents()
    {
        return $this->hasMany(CustomerActiveComponent::className(), ['component_id' => 'id']);
    }

    /**
     * Gets query for [[ShopOrderComponents]].
     *
     * @return \yii\db\ActiveQuery|ShopOrderComponentsQuery
     */
    public function getShopOrderComponents()
    {
        return $this->hasMany(ShopOrderComponents::className(), ['component_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ComponentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComponentQuery(get_called_class());
    }

    public function createHtmlDataParams()
    {
        return [
            'data-id' => $this->getPrimaryKey(),
            'data-name' => $this->name ?: '',
            'data-short_name' => $this->short_name ?: '',
            'data-price' => $this->price ?: 0,
            'data-price_discount' => $this->price_discount ?: 0,
            'data-image' => $this->getImageUrl(),
            'data-video' => $this->getVideoUrl(),
            'data-item_select_min' => $this->item_select_min ?? '',
            'data-item_select_max' => $this->item_select_max ?? '',
            //TODO: 'ед.' - сделать единицу по умолчанию (и перевод)
            'data-unit_name' => $this->unit ? $this->unit->name : 'ед.',
            'data-unit_value' => $this->unit_value ?? '',
            'data-unit_value_min' => $this->unit_value_min ?? '',
            'data-unit_value_max' => $this->unit_value_max ?? '',
            'data-unit_switch_group_id' => $this->unit_switch_group ?? '',
            'data-unit_switch_group_name' => $this->unitSwitchGroup ? $this->unitSwitchGroup->name : '',
        ];
    }

    public function getImageUrl()
    {
        //TODO: рандомайз ??
        if (isset($this->componentImages[0])) {
            return Url::to(Yii::$app->params['component_images']['url_path'] . $this->componentImages[0]->relative_path);
        }

        return Url::to('/img/question-man.jpg');
    }

    public function getVideoUrl()
    {
        //TODO: рандомайз ??
        if (isset($this->componentVideos[0])) {
            return Url::to(Yii::$app->params['component_videos']['url_path'] . $this->componentVideos[0]->relative_path);
        }

        return Url::to('/video/construct' . Yii::$app->params['debug-preview-path'] . '/default.gif');
    }
}
