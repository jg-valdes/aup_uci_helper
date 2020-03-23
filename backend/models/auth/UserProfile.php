<?php

namespace backend\models\auth;

use backend\models\BaseModel;
use backend\models\business\UserCoupon;
use common\models\GlobalFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property string $phone_mobile
 * @property string $phone_fixed
 * @property string $address
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property int $gender
 *
 * @property \webvimark\modules\UserManagement\models\User $user
 */
class UserProfile extends BaseModel
{
    /**
     * Gender options
     */
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 0;

    public $file;
    public $status;
    public $email;
    public $password;
    public $repeat_password;
    public $username;

    /**
     * @var array for presenting user Roles
     */
    public $roles;

    /**
     * @var array $labels for simulate relationship management
     */
    public $coupons;

    /**
     * @return string The base name for current model
     */
    public function getBaseName()
    {
        return StringHelper::basename(get_class($this));
    }

    /**
     * @inheritdoc
     */
    public function getBaseLink()
    {
        return "/user-profile";
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'gender'], 'integer'],
            [['first_name', 'last_name', 'email', 'username', 'gender'], 'required'],

            ['username', 'filter', 'filter' => function ($attribute) {
                return strtolower(trim($attribute));
            }],
            ['username', 'string', 'min' => 2, 'max' => 20, 'tooShort' => '{attribute} debe contener al menos 2 caracteres.', 'tooLong' => 'Solo se admiten como máximo 20 caracteres.'],
            ['username', 'match', 'pattern' => '/^[A-Za-z][A-Za-z_0-9]+$/', 'message' => 'Solo se permiten letras y números (sin espacios). Comenzando por una letra.'],

            [['email'], 'string', 'max' => 50],
            [['email'], 'email'],

            [['phone_mobile', 'phone_fixed'], 'string', 'max' => 15],

            [['created_at', 'updated_at', 'password', 'repeat_password'], 'safe'],
            [['first_name', 'last_name', 'avatar', 'address'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'match', 'pattern' => '/^[A-Za-z áéíóúñüÁÉÍÓÚÑÜ]+$/', 'message' => 'Solo se permiten letras'],

            [['file'], 'file'],

            [['password'], 'required', 'on' => ['newUser', 'changePassword']],
            ['password', 'string', 'min' => 4, 'tooShort' => '{attribute} debe contener al menos 4 caracteres.', 'on' => ['newUser', 'changePassword']],
            ['password', 'trim', 'on' => ['newUser', 'changePassword']],

            ['repeat_password', 'required', 'on' => ['changePassword']],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Usuario',
            'first_name' => 'Nombre',
            'last_name' => 'Apellidos',
            'avatar' => 'Avatar',
            'file' => 'Avatar',
            'created_at' => 'Creado en',
            'updated_at' => 'Actualizado en',
            'status' => 'Estado',
            'gender' => 'Género',
            'email' => 'Correo',
            'roles' => 'Roles',
            'password' => 'Contraseña',
            'repeat_password' => 'Repetir contraseña',
            'username' => 'Usuario',
            'phone_mobile' => 'Teléfono celular',
            'phone_fixed' => 'Teléfono fijo',
            'address' => 'Dirección',
            'coupons' => 'Cupones',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $isNewRecord = false;
        if (isset($this->password) && !empty($this->password)) {
            $isNewRecord = true;
            $modelUser = new User(['scenario' => 'newUser']);
            $modelUser->password = $this->password;
            $modelUser->repeat_password = $this->repeat_password;
        } else {
            $modelUser = $this->user;
        }
        $modelUser->status = $this->status;
        $modelUser->username = $this->username;

        $modelUser->email = $this->email;
        if ($modelUser->save() && $isNewRecord) {
            self::updateAll(['user_id' => $modelUser->id], ['id' => $this->id]);
            $this->user_id = $modelUser->id;
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function getFormattedRoles()
    {
        $this->roles = ArrayHelper::map(Role::getUserRoles($this->user_id), 'name', 'description');

        if (isset($this->roles) && !empty($this->roles)) {
            $formattedSpan = "<div class='text-left'>";
            foreach ($this->roles as $role => $description) {
                if (Yii::$app->user->isSuperadmin) {
                    $formattedSpan .= " " . GhostHtml::a(
                            $description,
                            ['/user-management/role/view', 'id' => $role],
                            ['target' => '_blank']
                        );
                } else {
                    $formattedSpan .= " " . $description;
                }

            }

            $formattedSpan .= "</div>";
        } else {
            $formattedSpan = Html::tag("span", "No tiene asociado aún.", ['class' => 'label label-danger']);
        }

        return $formattedSpan;
    }

    /** Returns true if avatar has some value, false other wise
     * @return bool
     */
    public function hasAvatar()
    {
        return (isset($this->avatar) && !empty($this->avatar));
    }

    public function getGender()
    {
        $gender = $this->gender == self::GENDER_MALE ? "Masculino" : "Femenino";
        return "{$gender}";
    }

    public function getEmail()
    {
        return isset($this->user->email) ? $this->user->email :
            "<span class='label label-danger'>No definido.</span>";
    }

    public function getMobile()
    {
        return isset($this->phone_mobile) && !empty($this->phone_mobile) ? $this->phone_mobile :
            "<span class='label label-danger'>No definido.</span>";
    }

    public function getPhone()
    {
        return isset($this->phone_fixed) && !empty($this->phone_fixed) ? $this->phone_fixed :
            "<span class='label label-danger'>No definido.</span>";
    }

    public function getAddress()
    {
        return isset($this->address) && !empty($this->address)? $this->address :
            "<span class='label label-danger'>No definido.</span>";
    }

    public function getStatus()
    {
        return $this->user->status == self::STATUS_ACTIVE ?
        "<span class='label label-success'>Activo</span>" :
            "<span class='label label-danger'>Inactivo</span>";
    }

    public function beforeSave($insert)
    {
        $this->file = UploadedFile::getInstance($this, 'file');

        if ($this->isNewRecord) {
            $user = new \webvimark\modules\UserManagement\models\User(['scenario'=>'newUser']);
            $user->email = $this->email;
            $user->username = $this->username;
            $user->password = $this->password;
            $user->repeat_password = $this->repeat_password;
            if($user->save()){
                $this->user_id = $user->id;
            }else{
                $this->addErrors($user->getErrors());
                return false;
            }

            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
            $this->user->updateAttributes([
                'email'=>$this->email,
                'updated_at'=>$this->updated_at,
                'status'=>$this->status
            ]);
        }

        return true; // TODO: Change the autogenerated stub
    }

    public function beforeDelete()
    {
        $this->removeAvatar();
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function removeAvatar () {
        $directory_resources = Yii::$app->getBasePath() . "/web/uploads/user_profiles/UserProfile_" . $this->id;
        if (is_dir($directory_resources)) {
            FileHelper::removeDirectory($directory_resources);
        }
    }

    public function validateUniqueEmail()
    {
        if (!isset($this->email) || empty($this->email)) {
            $this->addError('email', 'Correo no puede estar vacío.');
            return false;
        }
        if (($users = User::findAll(['email' => $this->email])) != null) {
            foreach ($users as $user) {
                if ($user->id != $this->user_id) {
                    $this->addError('email', 'Este correo ya ha sido utilizado por otro usuario.');
                    return false;
                }
            }
        }

        return true;
    }

    public function validateNewUser()
    {
        $hasErrors = !$this->validate();
        if ($this->isNewRecord) {
            if (User::findByUsername($this->username) != null) {
                $this->addError('username', 'Este usuario ya ha sido utilizado.');
                $hasErrors = true;
            }
        } else {
            if (($parent = User::findByUsername($this->username)) != null) {
                if ($parent->id != $this->user_id) {
                    $this->addError('username', 'Este usuario ya ha sido utilizado.');
                    $hasErrors = true;
                }
            }
        }
        return !$hasErrors && $this->validateUniqueEmail();

    }


    /**
     * This method generate the file of download
     * @return String that contain the relative path to file
     */
    public function generateFileRoute()
    {
        $baseUrl = Yii::$app->getHomeUrl();

        $fileRoute = "{$baseUrl}/uploads/user_profiles/UserProfile_{$this->id}/{$this->avatar}";

        return $fileRoute;
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public static function renderCurrentAvatar($options = [])
    {
        return self::renderAvatar(Yii::$app->user->getId(), $options);
    }

    public static function renderAvatar($user_id, $options = [])
    {
        $userProfile = self::find()->joinWith(['user'])->where(['user_id' => $user_id])->one();

        $baseFront = "";

        if (isset($userProfile)) {

            $img = $userProfile->hasAvatar() ?
                $userProfile->generateFileRoute() :
                $baseFront . GlobalFunctions::getUserDefaultAvatarUrl();
            $options = array_merge($options, [
                'alt' => $userProfile->user->username,
                'title' => $userProfile->getFullName(),
            ]);
        } else {

            $img = $baseFront . GlobalFunctions::getUserDefaultAvatarUrl();
        }



        return Html::img($img, $options);
    }

    public static function getCurrentUser()
    {
        return self::find()->joinWith(['user'])->where(['user_id' => Yii::$app->user->getId()])->one();
    }

    public static function getUserByEmail($email)
    {
        return self::find()->joinWith(['user'])->where(['like', 'user.email', $email])->one();
    }

    public static function getUserByComment($comment)
    {
        return self::find()->joinWith(['user'])->where(['user_id' => $comment->user_id])->one();
    }

    public static function getUserIdByUsername($username)
    {
        if (($user = User::findByUsername($username)) != null) {
            return $user->id;
        }
        return -1;
    }

    public static function getUserByUsername($username)
    {
        if (($user = self::find()->joinWith(['user'])->where(['user.username' => $username])->one()) != null) {
            return $user;
        }
        return null;
    }


    /**
     * @param bool $check_status
     * @return array
     */
    public static function getSelectMap($check_status = false)
    {
        $options = ['user.status' => self::STATUS_ACTIVE];
        return ArrayHelper::map(self::find()->joinWith(['user'])
            ->where($options)->asArray()->all(),
            'user_id', 'user.username');
    }


    public static function getMappedUsersBackend()
    {
        $query = User::find();
        if (!Yii::$app->user->isSuperadmin) {
            $query->where(['superadmin' => false]);
        }

        $query->andWhere(['NOT IN', 'id', array_keys(self::getSelectMap())]);

        return ArrayHelper::map($query->all(), 'id', 'username');
    }

    public function getIDLinkForUserParent()
    {
        if (Yii::$app->user->isSuperadmin) {
            return Html::a($this->user->username, ['/user-management/user/view', 'id' => $this->user_id]);
        } else {
            return $this->user->username;
        }
    }

    /**
     * @return string
     */
    public function getIDLinkForThisModel()
    {
        if (isset($this->id)) {
            return Html::a($this->getFullName(), [$this->getBaseLink() . "/view", 'id' => $this->getId()]);
        } else {
            return "<span class='label label-danger'>No definido</span>";
        }
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->user->accessToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->user->accessToken = null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()->joinWith(['user'])->where([
            'accessToken' => $token,
            'user.status' => self::STATUS_ACTIVE,
        ]);
    }
}
