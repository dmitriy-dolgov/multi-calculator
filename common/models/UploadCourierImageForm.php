<?php

namespace common\models;

use common\models\db\Component;
use common\models\db\ComponentImage;
use common\models\db\CourierImages;
use common\models\db\Profile;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadCourierImageForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;


    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true],
        ];
    }

    public static function imagePathUrl()
    {
        return Yii::$app->params['order_map']['courier']['images']['url_path'];
    }

    public static function imagePath()
    {
        return self::rootImagePlacePath() . self::imagePathUrl();
    }

    /**
     * Корень изображений безотносительно к пути.
     *
     * @return bool|string
     */
    public static function rootImagePlacePath()
    {
        return Yii::getAlias('@webroot');
    }

    public function removeImage(CourierImages $courierImagesModel, $type = 'sdf')
    {
        if ($courierImagesModel->$type) {
            $oldFilePath = self::rootImagePlacePath() . '/' . ltrim($courierImagesModel->$type,
                    DIRECTORY_SEPARATOR);
            if (is_file($oldFilePath)) {
                unlink($oldFilePath);
            }

            $courierImagesModel->$type = null;
        }
    }

    public function upload(CourierImages $courierImagesModel)
    {
        if (!$this->imageFile) {
            return true;
        }

        $uploadDir = self::imagePath();

        if ($this->validate()) {
            $filePath = false;
            for (; ;) {
                $filename = uniqid('', true) . '.' . $this->imageFile->extension;
                $filePath = $uploadDir . $filename;
                if (!file_exists($filePath)) {
                    break;
                }
            }

            if (!$this->imageFile->saveAs($filePath)) {
                Yii::error('Error saving uploaded file. Error code: ' . $this->imageFile->error . '. Path: ' . $filePath);
                return false;
            }

            $this->removeImage($courierImagesModel, 'run');

            $courierImagesModel->run = self::imagePathUrl() . $filename;
            $courierImagesModel->save();
        } else {
            $errors = serialize($this->errors);
            Yii::error('Validation errors: ' . $errors);
            return false;
        }

        return true;
    }
}
