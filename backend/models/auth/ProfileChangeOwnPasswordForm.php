<?php
/**
 * Created by PhpStorm.
 * User: JG
 * Date: 11/11/2018
 * Time: 23:16
 */

namespace backend\models\auth;

use webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm;

class ProfileChangeOwnPasswordForm extends ChangeOwnPasswordForm
{
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'current_password' => 'Contraseña actual'
        ]);
    }

    public function rules()
    {
        return [
            [['password', 'repeat_password'], 'required'],
            [['password', 'repeat_password', 'current_password'], 'string', 'max'=>255],
            [['password', 'repeat_password', 'current_password'], 'trim'],
            ['password', 'string', 'min' => 4, 'tooShort' => '{attribute} debe contener al menos 4 caracteres.', 'on' => ['changePassword']],
            ['repeat_password', 'compare', 'compareAttribute'=>'password'],

            ['current_password', 'required', 'except'=>'restoreViaEmail'],
            ['current_password', 'validateCurrentPassword', 'except'=>'restoreViaEmail'],

        ];
    }
}