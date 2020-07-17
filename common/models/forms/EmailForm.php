<?php

namespace common\models\forms;

use yii\base\Model;

class EmailForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'trim'],
            'emailPattern' => ['email', 'email'],
        ];
    }
}
