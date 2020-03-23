<?php

namespace backend\models\settings;

use backend\models\BaseModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "{{%system_config}}".
 *
 * @property int $id
 * @property string $name
 * @property string $json_config
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 */
class SystemConfig extends BaseModel
{
    /**
     * Constants system config names
     */

    /**
     * PHP path
     */
    const PHP_PATH = "PHP_PATH";
    const PHP_DEFAULT_PATH = "/opt/lampp/bin/";

    /**
     * Product settings
     */
    const PRODUCT = "PRODUCT";
    const PRODUCT_INDEX_LIMIT = "PRODUCT_INDEX_LIMIT";
    const PRODUCT_INDEX_VALUE = 16;
    const PRODUCT_RELATED_LIMIT = "PRODUCT_RELATED_LIMIT";
    const PRODUCT_RELATED_VALUE = 8;

    /**
     * Contact settings
     */
    const CONTACT_THANKS = "CONTACT_THANKS";
    const CONTACT_THANKS_MESSAGE = "CONTACT_THANKS_MESSAGE";
    const CONTACT_THANKS_VALUE = "Gracias por contactarnos, le responderemos tan pronto sea posible.";

    /**
     * Slider settings
     */
    const SLIDER = "SLIDER";
    const SLIDER_LIMIT = "SLIDER_LIMIT";
    const SLIDER_DEFAULT_LIMIT = 5;

    /**
     * Coupon settings
     */
    const COUPON = "COUPON";
    const COUPON_EXPIRE_YEARS = "COUPON_EXPIRE_YEARS";
    const COUPON_EXPIRE_USING = "COUPON_EXPIRE_USING";
    const COUPON_PERCENT = "COUPON_PERCENT";
    const COUPON_NAME = "COUPON_NAME";
    const COUPON_DEFAULT_EXPIRE_YEARS = 1;
    const COUPON_DEFAULT_EXPIRE_USING = 1;
    const COUPON_DEFAULT_PERCENT = 2.00;
    const COUPON_DEFAULT_NAME = "STANDARD";

    /**
     * Prices settings
     */
    const PRICES = "PRICES";
    const PRICES_CUC_EXCHANGE = "PRICES_CUC_EXCHANGE";
    const PRICES_DEFAULT_CUC_EXCHANGE = 25;

    /**
     * Username banned settings
     */
    const USERNAMES = "USERNAMES";
    const BANNED_USERNAMES = "BANNED_USERNAMES";
    private static $default_banned_usernames = ["pinga", "cojone", "maricon", "singar", "puta", "crica"];

    public static function getDefaultBannedUsernames()
    {
        return self::$default_banned_usernames;
    }

    /**
     * Returns a mapp of Setting Names that are available for creation
     * @return array
     */
    public function getSettingsConfigNameForCreation(){

        $configs = self::getSettingsConfigNamesMap();
        $exist = [];
        foreach ($configs AS $name){
            if(self::find()->where(['name'=>$name])->exists()){
                if(!$this->isNewRecord && isset($this->name) && !empty($this->name) && $this->name == $name){
                    continue;
                }else{
                    $exist[$name] = $name;
                }
            }
        }

        return array_diff($configs, $exist);
    }
    public static function getSettingsConfigNamesMap()
    {
        return [
            'PHP_PATH'=>'PHP_PATH',
            'PRODUCT'=>'PRODUCT',
            'CONTACT_THANKS'=>'CONTACT_THANKS',
            'SLIDER'=>'SLIDER',
            'COUPON'=>'COUPON',
            'PRICES'=>'PRICES'
        ];
    }

    public static function getSettingsConfigParamsMap()
    {
        return [
            'PHP_PATH'=>['PHP_DEFAULT_PATH'],
            'PRODUCT'=>['PRODUCT_INDEX_LIMIT', 'PRODUCT_RELATED_LIMIT'],
            'CONTACT_THANKS'=>['CONTACT_THANKS_MESSAGE'],
            'SLIDER'=>['SLIDER_LIMIT'],
            'COUPON'=>['COUPON_EXPIRE_YEARS', 'COUPON_EXPIRE_USING', 'COUPON_PERCENT', 'COUPON_NAME'],
            'PRICES'=>["PRICES_CUC_EXCHANGE", "PRICES_DEFAULT_CUC_EXCHANGE"]
        ];
    }

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
        return "/system-config";
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'json_config'], 'required'],
            [['json_config'], 'string'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['name'], 'filter', 'filter' => function ($attribute) {
                return strtoupper($attribute);
            }],

            [['json_config'], 'validateJSON'],
            [['name', 'description', 'json_config'], 'filter', 'filter'=>'\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Nombre'),
            'json_config' => Yii::t('app', 'Configuración'),
            'description' => Yii::t('app', 'Descripción'),
            'created_at' => Yii::t('app', 'Fecha de creación'),
            'updated_at' => Yii::t('app', 'Fecha de actualización'),
            'status' => Yii::t('app', 'Estado'),
        ];
    }

    /**
     * Validate json_config is a well formed JSON object
     * @return bool
     */
    public function validateJSON(){
        if(isset($this->json_config)){
            if((is_string($this->json_config) &&
                (is_object(json_decode($this->json_config)) ||
                    is_array(json_decode($this->json_config))))){
                return true;
            }else{
                $this->addError("json_config", Yii::t("backend", "Formato de cadena JSON no válido"));
                return false;
            }
        }
        return false;
    }


    /**
     * Returns the value for $param inside json_config attribute from SystemConfig model with $config_name identity
     * if no results found, then return null value
     * @param null $config_name
     * @param string $param the name of param name inside json_config attribute
     * @param null $default_value
     * @return mixed|null
     */
    public static function getSystemConfigParam($config_name = null, $param = '', $default_value = null)
    {
        if (($systemConfig = self::getSystemConfigByName($config_name)) != null) {
            $config = $systemConfig->getFormattedConfig();
            if (ArrayHelper::keyExists($param, $config)) {
                return ArrayHelper::getValue($config, $param, $default_value);
            }
        }

        return $default_value;
    }

    /**
     * Returns an array resulting from decode json_config attribute
     * @return mixed array
     */
    public function getFormattedConfig()
    {
        return json_decode($this->json_config, true);
    }

    /**
     * Return SystemConfig object if system config exists
     * @param string $config_name name of System Config to return
     * @return mixed SystemConfig|null
     */
    public static function getSystemConfigByName($config_name)
    {
        return self::find()->where(['status' => self::STATUS_ACTIVE, 'name' => $config_name])->one();
    }

    /**
     * Return true if system config exist
     * @param string $config_name name of System Config to check
     * @return boolean
     */
    public static function existSystemConfigByName($config_name)
    {
        return self::find()->where(['status' => self::STATUS_ACTIVE, 'name' => $config_name])->exists();
    }
}
