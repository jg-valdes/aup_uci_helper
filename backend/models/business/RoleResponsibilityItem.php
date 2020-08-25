<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%role_responsibility_item}}".
 *
 * @property int $id
 * @property int $role_responsibility_id
 * @property string $name
 * @property string $description
 * @property string $filename
 * @property int $views
 * @property int $downloads
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ArtifactResponsibilityItem[] $artifactResponsibilityItems
 * @property RoleResponsibility $roleResponsibility

 */
class RoleResponsibilityItem extends BaseModel
{
    public $artifacts;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role_responsibility_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_responsibility_id', 'name'], 'required'],
            [['role_responsibility_id', 'status', 'views', 'downloads'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'artifacts'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['filename'], 'file', 'extensions' => implode(',', static::getAllowedExtensions())],
            [['role_responsibility_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoleResponsibility::className(), 'targetAttribute' => ['role_responsibility_id' => 'id']],
            [['name', 'status', 'filename', 'description', 'role_responsibility_id'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'role_responsibility_id' => Yii::t('backend', 'Responsabilidad'),
            'name' => Yii::t('backend', 'Nombre'),
            'description' => Yii::t('backend', 'Descripción'),
            'filename' => Yii::t('backend', 'Recurso'),
            'views' => Yii::t('backend', 'Visitas'),
            'downloads' => Yii::t('backend', 'Descargas'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
            'artifacts' => Yii::t('backend', 'Artefactos'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtifactResponsibilityItems()
    {
        return $this->hasMany(ArtifactResponsibilityItem::className(), ['role_responsibility_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleResponsibility()
    {
        return $this->hasOne(RoleResponsibility::className(), ['id' => 'role_responsibility_id']);
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
        return "/role-responsibility-item";
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
     * Return a concatenated span labels with related Artifact links
     * @return string
     */
    public function getArtifactsLink()
    {
        $artifactsLink = [];
        foreach (ArtifactResponsibilityItem::getRelationsMapForRoleResponsibilityItem($this->id) as $id=>$name){
            $link = Html::a("<span class='label label-default'>{$name}</span>", ['/artifact/view', 'id'=>$id]);
            array_push($artifactsLink, $link);
        }

        if(empty($artifactsLink)){
            return GlobalFunctions::getNoValueSpan();
        }

        return implode(" ", $artifactsLink);
    }

    /** :::::::::::: BEGIN > Handle resource file ::::::::::::*/

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
        if(!file_exists("uploads/responsibility_item/") || !is_dir("uploads/responsibility_item/")){
            try{
                FileHelper::createDirectory("uploads/responsibility_item/", 0777);
            }catch (\Exception $exception){
                Yii::info("Error handling Role Responsibility Item folder resources");
            }

        }
        if(isset($this->filename) && !empty($this->filename) && $this->filename !== '')
            return "uploads/responsibility_item/{$this->filename}";
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
            return "uploads/responsibility_item/{$this->filename}";
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

    /** :::::::::::: END > Handle resource file ::::::::::::*/

    /**
     * Returns a formatted link for related discipline or a no value span message
     * @return string
     */
    public function getRoleResponsibilityLink()
    {
        if(isset($this->roleResponsibility)){
            return $this->roleResponsibility->getIDLinkForThisModel();
        }
        return GlobalFunctions::getNoValueSpan();
    }


    /**
     * Returns description value or a no value span message
     * @return string
     */
    public function getDescription()
    {
        if(isset($this->description) && !empty($this->description)){
            return $this->description;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getModelAsJson($includeRoleResponsibility = false)
    {
        $json = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'has_resource' => $this->hasResource(),
            'resource' => Url::to(['/role-responsibility-item/download', 'id'=>$this->id]),
            'views' => GlobalFunctions::getFormattedViewsCount($this->views),
            'downloads' => GlobalFunctions::getFormattedDownsCount($this->downloads),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        if($includeRoleResponsibility){
            $json['role_responsibility'] = $this->roleResponsibility->getModelAsJson();
        }

        return $json;
    }

}
