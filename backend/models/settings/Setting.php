<?php

namespace backend\models\settings;

use Yii;
use backend\models\BaseModel;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "setting".
 *
 * @property int $id
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $mini_header_logo
 * @property string $name
 * @property string $description
 * @property string $main_logo
 * @property string $header_logo
 * @property string $seo_keywords
 *
 */
class Setting extends BaseModel
{

    const SETTING_ID = 1;

    /**
     * Logo Types
     */
    const MAIN_LOGO = 1;
    const HEADER_LOGO = 2;
    const MINI_HEADER_LOGO = 3;

    public $file_main_logo;
    public $file_header_logo;
    public $file_mini_header_logo;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'address', 'email', 'name', 'description'], 'required'],
            [['phone', 'address', 'email', 'mini_header_logo', 'name', 'main_logo', 'header_logo'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Nombre'),
            'address' => Yii::t('app', 'Dirección'),
            'email' => Yii::t('app', 'Correo electrónico'),
            'phone' => Yii::t('app', 'Teléfono'),
            'description' => Yii::t('app', 'Descripción'),
            'main_logo' => Yii::t('app', 'Logo principal'),
            'file_main_logo' => Yii::t('app', 'Logo principal'),
            'header_logo' => Yii::t('app', 'Logo de cabecera'),
            'file_header_logo' => Yii::t('app', 'Logo de cabecera'),
            'mini_header_logo' => Yii::t('app', 'Mini logo de cabecera'),
            'file_mini_header_logo' => Yii::t('app', 'Mini logo de cabecera'),
        ];
    }


    /**
     * fetch stored logo file name with complete path
     * @return string
     */
    public function getImageFile($type)
    {
        switch ($type) {
            case self::MAIN_LOGO:
                {
                    if (isset($this->main_logo) && !empty($this->main_logo) && $this->main_logo !== '')
                        return 'images/' . $this->main_logo;
                    else
                        return null;
                    break;
                }
            case self::HEADER_LOGO:
                {
                    if (isset($this->header_logo) && !empty($this->header_logo) && $this->header_logo !== '')
                        return 'images/' . $this->header_logo;
                    else
                        return null;
                    break;
                }
            case self::MINI_HEADER_LOGO:
                {
                    if (isset($this->mini_header_logo) && !empty($this->mini_header_logo) && $this->mini_header_logo !== '')
                        return 'images/' . $this->mini_header_logo;
                    else
                        return null;
                    break;
                }
        }

    }

    /**
     * fetch stored logo url
     * @param $type // [1 => main_logo, 2 => header_logo, 3 => mini_logo_header]
     * @return string
     */
    public function getImageUrl($type)
    {
        // return a default logo placeholder if your source avatar is not found
        switch ($type) {
            case 1:
                {
                    $logo = isset($this->main_logo) ? $this->main_logo : 'main_logo.png';
                    break;
                }
            case 2:
                {
                    $logo = isset($this->header_logo) ? $this->header_logo : 'header_logo.png';
                    break;
                }
            case 3:
                {
                    $logo = isset($this->mini_header_logo) ? $this->mini_header_logo : 'mini_header_logo.png';
                    break;
                }
        }

        return 'images/' . $logo;
    }

    /**
     * Process upload of logo
     * @param $file_name_attribute //name of field to upload [[file_main_logo, file_header_logo, file_mini_header_logo]]
     * @param $type // [1 => main_logo, 2 => header_logo, 3 => mini_header_logo]
     * @return mixed the uploaded logo instance
     */
    public function uploadImage($file_name_attribute, $type)
    {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $logo = UploadedFile::getInstance($this, $file_name_attribute);

        // if no logo was uploaded abort the upload
        if (empty($logo)) {
            return false;
        }

        // store the source file name
        // $this->filename = $logo->name;
        $explode = explode('.', $logo->name);
        $ext = end($explode);
        $language_active = Yii::$app->language;

        // generate a unique file name
        switch ($type) {
            case 1:
                {
                    $this->main_logo = "main_logo_$language_active.{$ext}";
                    break;
                }
            case 2:
                {
                    $this->header_logo = "header_logo_$language_active.{$ext}";
                    break;
                }
            case 3:
                {
                    $this->mini_header_logo = "mini_header_logo_$language_active.{$ext}";
                    break;
                }
        }

        // the uploaded logo instance
        return $logo;
    }

    /**
     * Process deletion of logo
     * @param $type // [1 => main_logo, 2 => header_logo, 3 => mini_logo_header]
     * @return boolean the status of deletion
     */
    public function deleteImage($type)
    {
        $file = $this->getImageFile($type);

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }

        // if deletion successful, reset your file attributes
        switch ($type) {
            case 1:
                {
                    $this->main_logo = null;
                    break;
                }
            case 2:
                {
                    $this->header_logo = null;
                    break;
                }
            case 3:
                {
                    $this->mini_header_logo = null;
                    break;
                }
        }

        return true;
    }

    /**
     * get path logo of setting
     * @param integer $type // [1 => main_logo, 2 => header_logo, 3 => mini_header_logo]
     * @return string $logo_path
     */
    public static function getUrlLogoBySettingAndType($type)
    {
        $path = Url::to('@web/images/');

        if (($model = self::findOne(self::SETTING_ID)) !== null) {

            switch ($type) {
                case 1:
                    {
                        if ($model->main_logo === null || $model->main_logo === '') {
                            $url = $path . 'main_logo.png';
                        } else {
                            $url = $path . '' . $model->main_logo;
                        }
                        break;
                    }
                case 2:
                    {
                        if ($model->header_logo === null || $model->header_logo === '') {
                            $url = $path . 'header_logo.png';
                        } else {
                            $url = $path . '' . $model->header_logo;
                        }
                        break;
                    }
                case 3:
                    {
                        if ($model->mini_header_logo === null || $model->mini_header_logo === '') {
                            $url = $path . 'mini_header_logo.png';
                        } else {
                            $url = $path . '' . $model->mini_header_logo;
                        }
                        break;
                    }
            }

            return $url;


        }

        switch ($type) {
            case 1:
                {
                    $path_return = $path . 'main_logo.png';
                    break;
                }
            case 2:
                {
                    $path_return = $path . 'header_logo.png';
                    break;
                }
            case 3:
                {
                    $path_return = $path . 'mini_header_logo.png';
                    break;
                }
        }

        return $path_return;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        $model = Setting::findOne(self::SETTING_ID);
        if (isset($model) || empty($model->name)) {
            return $model->name;
        }
        return "Company Name";
    }

    /**
     * @return string
     */
    public static function getEmail()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if (!isset($model) || empty($model->email)) {
            return 'webfactorycuba@gmail.com';
        } else {
            return $model->email;
        }
    }

    /**
     * @return string
     */
    public static function getAddress()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if (!isset($model) || empty($model->address)) {
            return 'Habana, Cuba';
        } else {
            return $model->address;
        }
    }

    /**
     * @return string
     */
    public static function getPhone()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if (!isset($model) || empty($model->phone)) {
            return '(+53) 0000 0000';
        } else {
            return $model->phone;
        }
    }

    /**
     * @return string
     */
    public static function getDescription()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if (!isset($model) || empty($model->description)) {
            return 'Ofrecemos un servicio de calidad para todos nuestros clientes.';
        } else {
            return $model->description;
        }
    }

    /**
     * @return string
     */
    public static function getSeoKeywords()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if (!isset($model) || empty($model->seo_keywords)) {
            return 'Calzado PLuis, BFC, calzado, productos artesanales';
        } else {
            return $model->seo_keywords;
        }
    }

    /**
     * @return string formatted link for current model view
     */
    public function getIDLinkForThisModel()
    {
        if (isset($this->id)) {
            return Html::a($this->name, [$this->getBaseLink() . "/view", 'id' => $this->getId()]);
        } else {
            return "<span class='label label-danger'>No definido</span>";
        }
    }
}
