<?php

namespace common\models;

use common\models\db\Component;
use common\models\db\ComponentVideo;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadComponentVideoForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $videoFiles;


    public function rules()
    {
        return [
            [['videoFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10],
        ];
    }

    public static function videoPathUrl()
    {
        return Yii::$app->params['component_videos']['url_path'];
    }

    public static function videoPath()
    {
        return Yii::getAlias('@webroot' . self::videoPathUrl());
    }

    public function upload(Component $componentModel)
    {
        $uploadDir = self::videoPath();

        if ($this->validate()) {
            foreach ($this->videoFiles as $file) {

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

                $videoObj = new ComponentVideo();
                $videoObj->relative_path = $filename;
                $videoObj->link('component', $componentModel);
            }
        } else {
            $errors = serialize($this->errors);
            Yii::error('Validation errors: ' . $errors);
            return false;
        }

        return true;
    }
}
