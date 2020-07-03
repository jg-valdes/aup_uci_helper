<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%role_responsibility_item}}".
 *
 * @property int $id
 * @property int $role_responsibility_id
 * @property string $name
 * @property string $description
 * @property string $filename
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ArtifactResponsibilityItem[] $artifactResponsibilityItems
 * @property RoleResponsibility $roleResponsibility

 */
class RoleResponsibilityItem extends BaseModel
{

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
            [['role_responsibility_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'filename'], 'string', 'max' => 255],
            [['role_responsibility_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoleResponsibility::className(), 'targetAttribute' => ['role_responsibility_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'role_responsibility_id' => Yii::t('backend', 'Role Responsibility ID'),
            'name' => Yii::t('backend', 'Name'),
            'description' => Yii::t('backend', 'Description'),
            'filename' => Yii::t('backend', 'Filename'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
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
}
