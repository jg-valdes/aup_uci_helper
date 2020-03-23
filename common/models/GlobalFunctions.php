<?php

namespace common\models;

use backend\models\settings\Setting;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class GlobalFunctions
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const KEY_ENCRYPT_DECRYPT = 'key2019';

    /**
     * Available Roles
     */
    const ROLE_USER_FRONTED = "FrontendUser";
    const ROLE_ADMIN = "Admin";
    const ROLE_MODERATOR = "Moderator";

    /**
     * @return string
     */
    public static function getNoValueSpan()
    {
        return Html::tag("span", Yii::t("app", "No definido"), ['class' => 'label label-danger']);
    }

    /**
     * Function for get de current date of server
     * @param string $format Optional
     * @return date
     */
    public static function getCurrentDate($format = null)
    {
        date_default_timezone_set('America/Havana');

        if ($format === null)
            $currentDate = date("Y-m-d H:i:s");
        else
            $currentDate = date($format);

        return $currentDate;

    }

    /**
     * set a session flash message
     * @param string $class
     * @param string $message
     * @return mixed
     */
    public static function setFlashMessage($class, $message)
    {
        $session = Yii::$app->session;
        $session->setFlash($class, $message);
    }

    /**
     * set a session flash message
     * @param string $class
     * @param string $message
     * @return mixed
     */
    public static function addFlashMessage($class, $message)
    {
        $session = Yii::$app->session;
        $session->addFlash($class, $message);
    }

    /**
     * Get label with css to status of user
     * @param integer $value
     * @param boolean $show_yes_not
     * @return string
     */
    public static function getStatusValue($value, $show_yes_not = NULL)
    {
        if ($value === self::STATUS_ACTIVE) {
            if ($show_yes_not === NULL) {
                return Html::tag('span', Yii::t('app', 'Activo'), ['class' => 'label label-success']);
            } else {
                return Html::tag('span', Yii::t('app', 'SI'), ['class' => 'label label-success']);

            }
        } else {
            if ($show_yes_not === NULL) {
                return Html::tag('span', Yii::t('app', 'Inactivo'), ['class' => 'label label-danger']);

            } else {
                return Html::tag('span', Yii::t('app', 'NO'), ['class' => 'label label-danger']);

            }
        }

    }

    /**
     * @param $string
     * @param $key
     * @return string
     */
    public static function encrypt($string, $key = GlobalFunctions::KEY_ENCRYPT_DECRYPT)
    {
        $result = '';
        $total = strlen($string);
        for ($i = 0; $i < $total; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    /**
     * @param $string
     * @param $key
     * @return string
     */
    public static function decrypt($string, $key = GlobalFunctions::KEY_ENCRYPT_DECRYPT)
    {
        $result = '';
        $string = base64_decode($string);
        $total = strlen($string);

        for ($i = 0; $i < $total; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }

    /**
     * @param null $user_id
     * @return mixed
     */
    public static function getRol($user_id = NULL)
    {
        if (is_null($user_id))
            $user_id = Yii::$app->user->identity->id;

        return array_keys(Yii::$app->authManager->getRolesByUser($user_id))[0];
    }

    /**
     * Get roles list.
     * @return Array
     */
    public static function getRolesList()
    {
        $models = Yii::$app->authManager->getRoles();

        $value = (count($models) == 0) ? ['' => ''] : ArrayHelper::map($models, 'name', 'name');

        return $value;
    }

    /**
     * @return string
     */
    public static function getNoImageDefaultUrl()
    {
        $url = Url::to('@web/uploads/noimage_default.jpg');
        return $url;
    }

    /**
     * Returns a default url for and avatar
     * @return string
     */
    public static function getUserDefaultAvatarUrl()
    {
        $url = Url::to('@web/images/avatar_default.png');
        return $url;
    }

    /**
     * Función que dado el nombre de la carpeta y el nombre de del fichero devuelve la URL
     *
     * @param string $folder
     * @param string $name
     * @return string
     */
    public static function getFileByNamePath($folder, $name)
    {
        $url = Url::to('@web/uploads/' . $folder . '/' . $name);
        return $url;
    }

    /**
     * Process to deletion any file by url path
     *
     * @return boolean the status of deletion
     */
    public static function deleteFile($url_path)
    {
        // check if file exists on server
        if (empty($url_path) || !file_exists($url_path)) {
            return false;
        }

        // check if file can be deleted on server
        if (!unlink($url_path)) {
            return false;
        }

        return true;
    }

    /**
     * Function to get array of letters of alphabet to use in row excel
     *
     * @return array
     */
    public static function getArrayAlphabet()
    {
        $alphabet = [
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
            5 => 'E',
            6 => 'F',
            7 => 'G',
            8 => 'H',
            9 => 'I',
            10 => 'J',
            11 => 'K',
            12 => 'L',
            13 => 'M',
            14 => 'N',
            15 => 'O',
            16 => 'P',
            17 => 'Q',
            18 => 'R',
            20 => 'S',
            21 => 'T',
            22 => 'U',
            23 => 'V',
            24 => 'W',
            25 => 'X',
            26 => 'Y',
            27 => 'Z',
        ];

        return $alphabet;
    }

    /**
     * Function to convert a number in format int(111.11) or decimal(111.1111.111,52)
     *
     * @param $number
     * @param int $digits
     * @return string
     */
    public static function formatNumber($number, $digits = 0)
    {
        return number_format($number, $digits, ',', '.');
    }

    /***********************
     *  Extensions section *
     ***********************/

    public static function getImageFormats()
    {
        return ['jpg', 'jpeg', 'png', 'svg', 'psd', 'tiff', 'gif', 'bmp'];
    }

    public static function getAudioFormats()
    {
        return ['mp3', 'wav', 'ogg', 'wma', 'm4a'];
    }

    public static function getVideoFormats()
    {
        return ['avi', 'mkv', 'mpg', 'mpeg', 'mp4', 'mov', 'webm', 'wmv', 'flv'];
    }

    public static function getDocsFormats()
    {
        return ['txt', 'tex', 'ttf', 'pdf'];
    }

    public static function getWordFormats()
    {
        return ['docx', 'odt', 'doc', 'docm', 'dotx', 'dotm'];
    }

    public static function getExcelFormats()
    {
        return ['xls', 'xlsx', 'xlt', 'xml', 'csv', 'ods'];
    }

    public static function getPowerPointFormats()
    {
        return ['pptx', 'ppt', 'pps', 'ppsx', 'ppsm', 'odp'];
    }

    public static function getCompressFormats()
    {
        return ['rar', 'zip', '7z', '7zip', 'gz', 'gzip', 'tar', 'tar.gz', 'tgz'];
    }

    /**
     * Returns true if $ext is a audio, image or video for render a preview
     * @param $ext string
     * @return bool
     */
    public static function checkExtensionForPreview($ext)
    {
        return ArrayHelper::isIn($ext, self::getImageFormats()) ||
            ArrayHelper::isIn($ext, self::getVideoFormats()) ||
            ArrayHelper::isIn($ext, self::getAudioFormats());
    }

    /**
     * @param int $count
     * @param bool $withLabel
     * @return string
     */
    public static function getFormattedViewsCount($count = 0, $withLabel = false)
    {
        $key = $withLabel ? Yii::t("app", "vistas") : "";
        $formatted = "";
        switch ($count) {
            case 0:
                $formatted = $count;
                break;
            case $count > 999999:
                $formatted .= round($count /= 10000, 1);
                $key = "Kb " . $key;
                break;
            case $count > 999:
                $formatted .= round($count /= 1000, 1);
                $key = "K " . $key;
                break;
            default:
                $formatted = isset($count) ? $count : 0;
        }

        return $formatted . " " . $key;
    }

    /**
     * @param int $count
     * @param bool $withLabel
     * @return string
     */
    public static function getFormattedDownsCount($count = 0, $withLabel = false)
    {
        $key = $withLabel ? Yii::t("app", "descargas") : "";
        $formatted = "";
        switch ($count) {
            case 0:
                $formatted = $count;
                break;
            case $count >= 999999:
                $formatted .= round($count /= 1000000, 1);
                $key = "Kb " . $key;
                break;
            case $count >= 999:
                $formatted .= round($count /= 1000, 1);
                $key = "K " . $key;
                break;
            default:
                $formatted = isset($count) ? $count : 0;
        }

        return $formatted . " " . $key;
    }

    /**
     * @param string $date
     * @return string the formatted date to the syntax "Month day, year"
     */
    public static function formatDateToString($date, $format = "d/m/Y")
    {
        //Setting a default date for avoid try errors
        if (!isset($date)) $date = date("Y-m-d");

        $object = date_create($date);
        return date_format($object, $format);
    }

    public static function seoMetaTags()
    {
        echo Html::tag('meta', '', ['name' => 'robots', 'content' => 'index,follow']) . PHP_EOL;
        echo Html::tag('meta', '', ['name' => 'keywords', 'content' => Setting::getSeoKeywords()]) . "\n    ";
        echo Html::tag('meta', '', ['name' => 'author', 'content' => 'WebFactory (Cuba)']) . PHP_EOL;
    }
} 