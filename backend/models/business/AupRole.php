<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%aup_role}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $views
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RoleResponsibility[] $roleResponsibilities

 */
class AupRole extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%aup_role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['status', 'views'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name', 'description', 'status'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Nombre'),
            'description' => Yii::t('backend', 'Descripción'),
            'views' => Yii::t('backend', 'Visitas'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleResponsibilities()
    {
        return $this->hasMany(RoleResponsibility::className(), ['aup_role_id' => 'id']);
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
        return "/aup-role";
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

    public function getDescription()
    {
        if(isset($this->description) && !empty($this->description)){
            return $this->description;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getModelAsJson($includeRoleResponsibilities = false)
    {
        $json = [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        if($includeRoleResponsibilities){
            $responsibilities = [];
            foreach ($this->roleResponsibilities as $responsibility){
                array_push($responsibilities, $responsibility->getModelAsJson());
            }
            $json['responsibilities'] = $responsibilities;
        }

        return $json;
    }
}
