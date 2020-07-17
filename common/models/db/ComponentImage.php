<?php

namespace common\models\db;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "component_image".
 *
 * @property int $id
 * @property int $component_id
 * @property string $relative_path
 * @property string $deleted_at
 *
 * @property Component $component
 */
class ComponentImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'component_image';
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'deleted_at' => function () {
                        return date('Y-m-d H:i:s');
                    },
                ],
                'replaceRegularDelete' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['component_id'], 'integer'],
            [['deleted_at'], 'safe'],
            [['relative_path'], 'string', 'max' => 255],
            [
                ['component_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Component::className(),
                'targetAttribute' => ['component_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'component_id' => 'Component ID',
            'relative_path' => 'Relative Path',
            'deleted_at' => 'Deleted at',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponent()
    {
        return $this->hasOne(Component::className(), ['id' => 'component_id']);
    }

    /**
     * {@inheritdoc}
     * @return ComponentImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComponentImageQuery(get_called_class());
    }

    public function upload($files)
    {
        $uploadDir = Yii::getAlias('@webroot' . Yii::$app->params['component_images']['url_path']);

        $relativePaths = [];

        if ($files && $this->validate()) {
            foreach ($files as $file) {

                while (true) {
                    $filename = uniqid('', true) . '.' . $file->extension;
                    if (!file_exists($uploadDir . $filename)) {
                        break;
                    }
                }

                $file->saveAs($uploadDir . $filename);
                $relativePaths[] = $filename;
            }
        }

        return $relativePaths;
    }
}
