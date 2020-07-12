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
 * @property Scenario $aupScenario

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
    public function getAupScenario()
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
        return "/scenario";
    }

    /**
    * Returns a link that represents current object model
    * @return string
    *
    */
    public function getIDLinkForThisModel()
    {
        if (isset($this->aupScenario)) {
            return Html::a($this->aupScenario->name, [$this->getBaseLink() . "/view", 'id' => $this->scenario_id]);
        } else {
            return GlobalFunctions::getNoValueSpan();
        }
    }

    /** :::::::::::: END > Abstract Methods and Overrides ::::::::::::*/

    /**
     * @param $scenarioId int Scenario ID
     * @param $artifactId int Artifact ID
     * @return bool
     */
    public static function addRelation($scenarioId, $artifactId)
    {
        return (new self([
            'status' => self::STATUS_ACTIVE,
            'artifact_id' => $artifactId,
            'scenario_id' => $scenarioId
        ]))->save();
    }

    /**
     * @param $scenarioId int Scenario ID
     * @param $artifactId int Artifact ID
     * @return bool|false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteRelation($scenarioId, $artifactId)
    {
        if(self::existRelation($scenarioId, $artifactId)){
            return static::findOne(['artifact_id' => $artifactId, 'scenario_id' => $scenarioId])->delete();
        }

        return true;
    }

    /**
     * @param $scenarioId int Scenario ID
     * @param $artifactId int Artifact ID
     * @return bool
     */
    public static function existRelation($scenarioId, $artifactId)
    {
        return static::find()->where(['artifact_id' => $artifactId, 'scenario_id' => $scenarioId])->exists();
    }

    /**
     * Returns all rows of Artifacts related to a Scenario
     * @param int $scenarioId Scenario ID
     * @return array
     */
    public static function getRelationsForScenario($scenarioId)
    {
        return self::getRelations($scenarioId);
    }

    /**
     * Returns a map of Artifacts related to a Scenario
     * @param int $scenarioId Scenario ID
     * @return array
     */
    public static function getRelationsMapForScenario($scenarioId)
    {
        return self::getRelationsMap($scenarioId);
    }

    /**
     * Returns all rows of Scenarios related to an Artifact
     * @param int $artifactId Artifact ID
     * @return array
     */
    public static function getRelationsForArtifact($artifactId)
    {
        return self::getRelations($artifactId, false);
    }

    /**
     * Returns a map of Scenarios related to an Artifact
     * @param int $artifactId Artifact ID
     * @return array
     */
    public static function getRelationsMapForArtifact($artifactId)
    {
        return self::getRelationsMap($artifactId, false);
    }

    /**
     * Returns all tuples for relations for Model ID
     * @param int $modelId Scenario|Artifact ID
     * @param bool $forScenario
     * @return array|\yii\db\ActiveRecord[]
     */
    private static function getRelations($modelId, $forScenario=true)
    {
        $modelAttr = $forScenario? 'scenario_id' : 'artifact_id';
        return static::find()->where(["{$modelAttr}" => $modelId])->all();
    }

    /**
     * Returns the relation map using id and name map
     * @param int $modelId Scenario|Artifact ID
     * @param bool $forScenario true for search by scenario as default
     * @return array
     */
    private static function getRelationsMap($modelId, $forScenario=true)
    {
        $modelAttr = $forScenario? 'scenario_id' : 'artifact_id';
        $modelAttrNegative = $forScenario? 'artifact_id' : 'scenario_id';
        $modelJoin = $forScenario? 'artifact' : 'aupScenario';
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
