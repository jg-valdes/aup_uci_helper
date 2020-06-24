<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%role_responsibility}}".
 *
 * @property int $id
 * @property int $aup_role_id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AupRole $aupRole
 * @property RoleResponsibilityItem[] $roleResponsibilityItems

 */
class RoleResponsibility extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role_responsibility}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aup_role_id', 'name'], 'required'],
            [['aup_role_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['aup_role_id'], 'exist', 'skipOnError' => true, 'targetClass' => AupRole::className(), 'targetAttribute' => ['aup_role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'aup_role_id' => Yii::t('backend', 'Aup Role ID'),
            'name' => Yii::t('backend', 'Name'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAupRole()
    {
        return $this->hasOne(AupRole::className(), ['id' => 'aup_role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleResponsibilityItems()
    {
        return $this->hasMany(RoleResponsibilityItem::className(), ['role_responsibility_id' => 'id']);
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
        return "/role-responsibility";
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

}
