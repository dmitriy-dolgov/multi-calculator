<?php

namespace common\models\forms;

use Da\User\Form\ResendForm as BaseForm;

class ResendForm extends BaseForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'trim'],
            'emailPattern' => ['email', 'email'],
        ];
    }
}
