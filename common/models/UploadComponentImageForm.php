<?php

namespace common\models;

use common\models\db\Component;
use common\models\db\ComponentImage;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadComponentImageForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;


    public function rules()
    {
        return [
            //[['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10],
        ];
    }

    public static function imagePath()
    {
        return Yii::getAlias('@webroot' . Yii::$app->params['component_images']['url_path']);
    }

    public function upload(Component $componentModel)
    {
        $uploadDir = self::imagePath();

        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {

                $filePath = false;
                while (true) {
                    $filename = uniqid('', true) . '.' . $file->extension;
                    $filePath = $uploadDir . $filename;
                    if (!file_exists($filePath)) {
                        break;
                    }
                }

                if (!$file->saveAs($filePath)) {
                    Yii::error('Error saving uploaded file. Error code: ' . $file->error . '. Path: ' . $filePath);
                    return false;
                }

                $imageObj = new ComponentImage();
                $imageObj->relative_path = $filename;
                $imageObj->link('component', $componentModel);
            }
        } else {
            $errors = serialize($this->errors);
            Yii::error('Validation errors: ' . $errors);
            return false;
        }

        return true;
    }
}
