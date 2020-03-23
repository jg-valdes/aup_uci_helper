<?php
namespace backend\models\auth;

use backend\models\UserProfile;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var UserProfile
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Token de confirmación no puede estar en blanco.');
        }
        $this->_user = UserProfile::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Token de confirmación inválido.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $userProfile = $this->_user;
        $userProfile->user->setPassword($this->password);
        $userProfile->removePasswordResetToken();

        return $userProfile->user->save(false);
    }
}
