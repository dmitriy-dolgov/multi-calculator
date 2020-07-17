<?php

namespace common\models;

use common\models\db\Profile;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadProfileIconImageForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $iconImageFile;


    public function rules()
    {
        return [
            //[['iconImageFile'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 1],
            [['iconImageFile'], 'file', 'skipOnEmpty' => true],
        ];
    }

    public static function imagePathUrl()
    {
        return Yii::$app->params['customer_images']['url_path'];
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

    public function removeIconImage(Profile $profileModel, $save = true)
    {
        if ($profileModel->icon_image_path) {
            $oldFilePath = self::rootImagePlacePath() . '/' . ltrim($profileModel->icon_image_path,
                    DIRECTORY_SEPARATOR);
            if (is_file($oldFilePath)) {
                unlink($oldFilePath);
            }

            $profileModel->icon_image_path = null;

            if ($save) {
                $profileModel->save();
            }
        }
    }

    public function upload(Profile $profileModel)
    {
        if (!$this->iconImageFile) {
            return true;
        }

        $uploadDir = self::imagePath();

        if ($this->validate()) {
            $filePath = false;
            while (true) {
                $filename = uniqid('', true) . '.' . $this->iconImageFile->extension;
                $filePath = $uploadDir . $filename;
                if (!file_exists($filePath)) {
                    break;
                }
            }

            if (!$this->iconImageFile->saveAs($filePath)) {
                Yii::error('Error saving uploaded file. Error code: ' . $this->iconImageFile->error . '. Path: ' . $filePath);
                return false;
            }

            $this->removeIconImage($profileModel, false);

            $profileModel->icon_image_path = self::imagePathUrl() . $filename;
            $profileModel->save();
        } else {
            $errors = serialize($this->errors);
            Yii::error('Validation errors: ' . $errors);
            return false;
        }

        return true;
    }
}
