<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%artifact_responsibility_item}}".
 *
 * @property int $id
 * @property int $artifact_id
 * @property int $role_responsibility_item_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Artifact $artifact
 * @property RoleResponsibilityItem $roleResponsibilityItem

 */
class ArtifactResponsibilityItem extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%artifact_responsibility_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['artifact_id', 'role_responsibility_item_id'], 'required'],
            [['artifact_id', 'role_responsibility_item_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['artifact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artifact::className(), 'targetAttribute' => ['artifact_id' => 'id']],
            [['role_responsibility_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoleResponsibilityItem::className(), 'targetAttribute' => ['role_responsibility_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'artifact_id' => Yii::t('backend', 'Artifact ID'),
            'role_responsibility_item_id' => Yii::t('backend', 'Role Responsibility Item ID'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtifact()
    {
        return $this->hasOne(Artifact::className(), ['id' => 'artifact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleResponsibilityItem()
    {
        return $this->hasOne(RoleResponsibilityItem::className(), ['id' => 'role_responsibility_item_id']);
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
        return "/artifact-responsibility-item";
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
     * @param $itemId int RoleResponsibilityItem ID
     * @param $artifactId int Artifact ID
     * @return bool
     */
    public static function addRelation($itemId, $artifactId)
    {
        return (new self([
            'status' => self::STATUS_ACTIVE,
            'artifact_id' => $artifactId,
            'role_responsibility_item_id' => $itemId
        ]))->save();
    }

    /**
     * @param $itemId int RoleResponsibilityItem ID
     * @param $artifactId int Artifact ID
     * @return bool|false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteRelation($itemId, $artifactId)
    {
        if(self::existRelation($itemId, $artifactId)){
            return static::findOne(['artifact_id' => $artifactId, 'role_responsibility_item_id' => $itemId])->delete();
        }

        return true;
    }

    /**
     * @param $itemId int RoleResponsibilityItem ID
     * @param $artifactId int Artifact ID
     * @return bool
     */
    public static function existRelation($itemId, $artifactId)
    {
        return static::find()->where(['artifact_id' => $artifactId, 'role_responsibility_item_id' => $itemId])->exists();
    }

    /**
     * Returns all rows of Artifacts related to a RoleResponsibilityItem
     * @param int $itemId RoleResponsibilityItem ID
     * @return array
     */
    public static function getRelationsForRoleResponsibilityItem($itemId)
    {
        return self::getRelations($itemId);
    }

    /**
     * Returns a map of Artifacts related to a RoleResponsibilityItem
     * @param int $itemId RoleResponsibilityItem ID
     * @return array
     */
    public static function getRelationsMapForRoleResponsibilityItem($itemId)
    {
        return self::getRelationsMap($itemId);
    }

    /**
     * Returns all rows of RoleResponsibilityItems related to an Artifact
     * @param int $artifactId Artifact ID
     * @return array
     */
    public static function getRelationsForArtifact($artifactId)
    {
        return self::getRelations($artifactId, false);
    }

    /**
     * Returns a map of RoleResponsibilityItems related to an Artifact
     * @param int $artifactId Artifact ID
     * @return array
     */
    public static function getRelationsMapForArtifact($artifactId)
    {
        return self::getRelationsMap($artifactId, false);
    }

    /**
     * Returns all tuples for relations for Model ID
     * @param int $modelId RoleResponsibilityItem|Artifact ID
     * @param bool $forRoleResponsibilityItem
     * @return array|\yii\db\ActiveRecord[]
     */
    private static function getRelations($modelId, $forRoleResponsibilityItem=true)
    {
        $modelAttr = $forRoleResponsibilityItem? 'role_responsibility_item_id' : 'artifact_id';
        return static::find()->where(["{$modelAttr}" => $modelId])->all();
    }

    /**
     * Returns the relation map using id and name map
     * @param int $modelId RoleResponsibilityItem|Artifact ID
     * @param bool $forRoleResponsibilityItem true for search by Role Responsibility Item as default
     * @return array
     */
    private static function getRelationsMap($modelId, $forRoleResponsibilityItem=true)
    {
        $modelAttr = $forRoleResponsibilityItem? 'role_responsibility_item_id' : 'artifact_id';
        $modelAttrNegative = $forRoleResponsibilityItem? 'artifact_id' : 'role_responsibility_item_id';
        $modelJoin = $forRoleResponsibilityItem? 'artifact' : 'roleResponsibilityItem';
        $models = static::find()
            ->joinWith(["{$modelJoin}"])->where(["{$modelAttr}" => $modelId])
            ->asArray()->all();

        $array_map = [];
        if(count($models)>0)
        {
            foreach ($models AS $model)
            {
                $array_map[$model["{$modelAttrNegative}"]] = $model["{$modelJoin}"]["name"];
            }
        }
        return $array_map;
    }

}
