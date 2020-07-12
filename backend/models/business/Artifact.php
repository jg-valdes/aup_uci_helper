<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%artifact}}".
 *
 * @property int $id
 * @property int $process_id
 * @property string $name
 * @property string $description
 * @property string $filename
 * @property int $views
 * @property int $downloads
 * @property int $order
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Process $process
 * @property ArtifactResponsibilityItem[] $artifactResponsibilityItems
 * @property ScenarioArtifact[] $scenarioArtifacts
 * @property Scenario[] $scenarios

 */
class Artifact extends BaseModel
{
    public $aup_scenarios;
    public $role_responsibilities;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%artifact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['process_id', 'name'], 'required'],
            [['process_id', 'order', 'status', 'views', 'downloads'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'aup_scenarios', 'role_responsibilities'], 'safe'],
            [['name'], 'string', 'max' => 255],
//            [['order'], 'unique', 'targetAttribute'=>['order', 'process_id']],
            [['filename'], 'file', 'extensions' => implode(',', static::getAllowedExtensions())],
            [['process_id'], 'exist', 'skipOnError' => true, 'targetClass' => Process::className(), 'targetAttribute' => ['process_id' => 'id']],
            [['name', 'status', 'filename', 'description', 'process_id', 'order'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * Return the allowed extensions for resources
     * @return array
     */
    public static function getAllowedExtensions()
    {
        return array_merge(
            GlobalFunctions::getDocsFormats(),
            GlobalFunctions::getImageFormats(),
            GlobalFunctions::getPowerPointFormats(),
            GlobalFunctions::getExcelFormats(),
            GlobalFunctions::getCompressFormats(),
            GlobalFunctions::getWordFormats()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'process_id' => Yii::t('backend', 'Proceso'),
            'name' => Yii::t('backend', 'Nombre'),
            'description' => Yii::t('backend', 'Descripción'),
            'filename' => Yii::t('backend', 'Recurso'),
            'views' => Yii::t('backend', 'Visitas'),
            'downloads' => Yii::t('backend', 'Descargas'),
            'order' => Yii::t('backend', 'Órden'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
            'aup_scenarios' => Yii::t('backend', 'Escenarios'),
            'role_responsibilities' => Yii::t('backend', 'Responsabilidades de Rol'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcess()
    {
        return $this->hasOne(Process::className(), ['id' => 'process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtifactResponsibilityItems()
    {
        return $this->hasMany(ArtifactResponsibilityItem::className(), ['artifact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScenarioArtifacts()
    {
        return $this->hasMany(ScenarioArtifact::className(), ['artifact_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getScenarios()
    {
        return $this->hasMany(Scenario::className(), ['id' => 'scenario_id'])->viaTable('scenario_artifact', ['artifact_id' => 'id']);
    }

    /** :::::::::::: START > Abstract Methods and Overrides ::::::::::::*/

    /**
    * @return string The base name for current model, it must be implemented on each child
    */
    public function getBaseName()
    {
        return StringHelper::basename(get_class($this));
    }

    /**
    * @return string base route to model links, default to '/'
    */
    public function getBaseLink()
    {
        return "/artifact";
    }

    /**
    * Returns a link that represents current object model
    * @return string
    *
    */
    public function getIDLinkForThisModel()
    {
        $id = $this->getRepresentativeAttrID();
        if (isset($this->$id)) {
            $name = $this->getRepresentativeAttrName();
            return Html::a($this->$name, [$this->getBaseLink() . "/view", 'id' => $this->getId()]);
        } else {
            return GlobalFunctions::getNoValueSpan();
        }
    }

    /** :::::::::::: END > Abstract Methods and Overrides ::::::::::::*/

    /**
     * @return string span link to related Process
     */
    public function getProcessLink()
    {
        if(isset($this->process)){
            return $this->process->getIDLinkForThisModel();
        }
        return GlobalFunctions::getNoValueSpan();
    }

    /**
     * Return a concatenated span labels with related Scenario links
     * @return string
     */
    public function getScenariosLink()
    {
        $scenariosLink = [];
        foreach (ScenarioArtifact::getRelationsMapForArtifact($this->id) as $id=>$name){
            $link = Html::a("<span class='label label-default'>{$name}</span>", ['/scenario/view', 'id'=>$id]);
            array_push($scenariosLink, $link);
        }

        if(empty($scenariosLink)){
            return GlobalFunctions::getNoValueSpan();
        }
        return implode(" ", $scenariosLink);
    }

    /**
     * Return a concatenated span labels with related RoleResponsibilityItem links
     * @return string
     */
    public function getRoleResponsibilityItemsLink()
    {
        $responsibilityLink = [];
        foreach (ArtifactResponsibilityItem::getRelationsMapForArtifact($this->id) as $id=>$name){
            $link = Html::a("<span class='label label-default'>{$name}</span>", ['/role-responsibility-item/view', 'id'=>$id]);
            array_push($responsibilityLink, $link);
        }

        if(empty($responsibilityLink)){
            return GlobalFunctions::getNoValueSpan();
        }
        return implode(" ", $responsibilityLink);
    }

    public function getDescription()
    {
        if(isset($this->description) && !empty($this->description)){
            return $this->description;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    /**
     * Returns the last order of any Artifact related with Process id param
     * @param $processId integer Process ID
     * @return mixed
     */
    public static function getLastOrderForArtifact($processId)
    {
        return self::find()->joinWith(['process'])->where(['process_id' => $processId])->max('artifact.order');
    }

    /**
     * @return boolean true if exists stored resource
     */
    public function hasResource()
    {
        return (isset($this->filename) && !empty($this->filename) && $this->filename !== '');
    }

    /**
     * fetch stored resource file name with complete path
     * @return string
     */
    public function getResourceFile()
    {
        if(!file_exists("uploads/artifacts/") || !is_dir("uploads/artifacts/")){
            try{
                FileHelper::createDirectory("uploads/artifacts/", 0777);
            }catch (\Exception $exception){
                Yii::info("Error handling Artifact folder resources");
            }

        }
        if(isset($this->filename) && !empty($this->filename) && $this->filename !== '')
            return "uploads/artifacts/{$this->filename}";
        else
            return null;

    }

    /**
     * fetch stored resource url
     * @return string
     */
    public function getResourceUrl()
    {
        if($this->hasResource()){
            return "uploads/artifacts/{$this->filename}";
        }else{
            return GlobalFunctions::getNoImageDefaultUrl();
        }

    }

    /**
     * Process upload of resource
     * @return mixed the uploaded resource instance
     */
    public function uploadResource() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'filename');

        // if no resource was uploaded abort the upload
        if (empty($image)) {
            return false;
        }

        // store the source file name
        // $this->filename = $image->name;
        $explode= explode('.',$image->name);
        $ext = end($explode);
        $hash_name = GlobalFunctions::generateRandomString(10);
        $this->filename = "{$hash_name}.{$ext}";

        // the uploaded resource instance
        return $image;
    }

    /**
     * Process deletion of logo
     * @return boolean the status of deletion
     */
    public function deleteResource() {
        $file = $this->getResourceFile();

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        try{
            if (!unlink($file)) {
                return false;
            }
        }catch (\Exception $exception){
            Yii::info("Error deleting resource on Artifact: " . $file);
            Yii::info($exception->getMessage());
            return false;
        }

        // if deletion successful, reset your file attributes
        $this->filename = null;

        return true;
    }

    /**
     * Returns the file extension
     * @return string
     */
    public function getResourceExtension()
    {
        if($this->hasResource()){
            $pieces = explode(".", $this->filename);
            return $pieces[count($pieces)-1];
        }
        return '';
    }
}
