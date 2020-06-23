<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%scenario_artifact}}".
 *
 * @property int $id
 * @property int $scenario_id
 * @property int $artifact_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Artifact $artifact
 * @property Scenario $scenario0

 */
class ScenarioArtifact extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%scenario_artifact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scenario_id', 'artifact_id'], 'required'],
            [['scenario_id', 'artifact_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['artifact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artifact::className(), 'targetAttribute' => ['artifact_id' => 'id']],
            [['scenario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Scenario::className(), 'targetAttribute' => ['scenario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'scenario_id' => Yii::t('backend', 'Scenario ID'),
            'artifact_id' => Yii::t('backend', 'Artifact ID'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
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
    public function getScenario0()
    {
        return $this->hasOne(Scenario::className(), ['id' => 'scenario_id']);
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
        return "/scenario-artifact";
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
