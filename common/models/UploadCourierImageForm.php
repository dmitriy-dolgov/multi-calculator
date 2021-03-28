<?php

namespace common\models;

use common\models\db\Component;
use common\models\db\ComponentImage;
use common\models\db\CourierImages;
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

    public static function imagePath()
    {
        return Yii::getAlias('@webroot' . Yii::$app->params['order_map']['courier']['images']['url_path']);
    }

    public function upload()
    {
        $uploadDir = self::imagePath();

        if ($this->validate()) {
            foreach ($this->imageFile as $file) {

                $filePath = false;
                while (true) {
                    $filename = uniqid('', true) . '.' . $file->extension;
                    $filePath = $uploadDir . $filename;
                    if (!file_exists($filePath)) {
                        break;
                    }
                }

                if (!$file->saveAs($filePath)) {
                    Yii::error('Error saving uploaded file (courier images). Error code: ' . $file->error . '. Path: ' . $filePath);
                    return false;
                }

                $imageObj = new CourierImages();
                $imageObj->run = $filename;
                $imageObj->wait = $filename;
                $imageObj->save();
            }
        } else {
            $errors = serialize($this->errors);
            Yii::error('Validation errors: ' . $errors);
            return false;
        }

        return true;
    }
}
