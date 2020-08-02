<?php

namespace backend\models\knn;

use Yii;
use backend\models\business\Scenario;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%ia_case}}".
 *
 * @property int $id
 * @property int $scenario_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CaseMetric[] $caseMetrics
 * @property Scenario $scenario0

 */
class IaCase extends BaseModel
{
    public $metrics;

    /**
     * @var array for keep metrics in calculate distance format
     */
    public $_currentMetrics;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ia_case}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scenario_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'scenario_id' => Yii::t('backend', 'Escenario'),
            'metrics' => Yii::t('backend', 'Métricas'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseMetrics()
    {
        return $this->hasMany(CaseMetric::className(), ['ia_case_id' => 'id']);
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
        return "/ia-case";
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

    public function getScenarioLink()
    {
        if(isset($this->scenario0)){
            return $this->scenario0->getIDLinkForThisModel();
        }
        return GlobalFunctions::getNoValueSpan();
    }


    /** :::::::::::: BEGIN > KNN functions ::::::::::::*/

    /**
     * @param IaCase $targetCase
     * @return float|int
     */
    public function calculateDistance(IaCase $targetCase)
    {
        if(empty($this->_currentMetrics)){
            $this->fillMetricsForCalculateDistance();
        }
        $targetCase->fillMetricsForCalculateDistance();

        if(empty($targetCase->_currentMetrics)){
          return $this->getFullDistance();
        }
        if(empty($this->_currentMetrics)){
            return $targetCase->getFullDistance();
        }

        $distance = 0;
        foreach ($this->_currentMetrics as $case){
            if(in_array($case['metric_id'], array_column($targetCase->_currentMetrics,'metric_id'))){
                foreach ($targetCase->_currentMetrics as $target){
                    if($case['metric_id'] == $target['metric_id']){
                        $v = 0; // Value
                        $w = 1; // Weight Multiply
                        if($case['metric_item_id'] == $target['metric_item_id']){
                            $v = 1;
                            if($target['weight'] > 0){
                                $w = $target['weight'];
                            }
                        }
                        $distance += $v*$w;
                    }
                }
            }
        }

        return $distance;
    }

    public function getFullDistance()
    {
        $distance = 0;
        foreach ($this->_currentMetrics as $case){
            $w = 1;
            if($case['weight'] > 0){
                $w = $case['weight'];
            }
            $distance += 1*$w;
        }
        return $distance;
    }

    public function fillMetricsForCalculateDistance()
    {
        $this->_currentMetrics = [];
        foreach ($this->caseMetrics as $relation){
            array_push($this->_currentMetrics, [
               'metric_id' => $relation->metric_id,
               'metric_item_id' => $relation->metric_item_id,
               'weight' => $relation->metricItem->getWeight()
            ]);
        }
    }

    /** :::::::::::: END > KNN functions ::::::::::::*/
}
