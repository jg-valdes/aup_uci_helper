<?php

namespace backend\models\knn;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%case_metric}}".
 *
 * @property int $id
 * @property int $metric_id
 * @property int $ia_case_id
 * @property int $metric_item_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property IaCase $iaCase
 * @property Metric $metric
 * @property MetricItem $metricItem

 */
class CaseMetric extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%case_metric}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metric_id', 'ia_case_id', 'metric_item_id'], 'required'],
            [['metric_id', 'ia_case_id', 'metric_item_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ia_case_id'], 'exist', 'skipOnError' => true, 'targetClass' => IaCase::className(), 'targetAttribute' => ['ia_case_id' => 'id']],
            [['metric_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metric::className(), 'targetAttribute' => ['metric_id' => 'id']],
            [['metric_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetricItem::className(), 'targetAttribute' => ['metric_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'metric_id' => Yii::t('backend', 'Metric ID'),
            'ia_case_id' => Yii::t('backend', 'Ia Case ID'),
            'metric_item_id' => Yii::t('backend', 'Metric Item ID'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIaCase()
    {
        return $this->hasOne(IaCase::className(), ['id' => 'ia_case_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetric()
    {
        return $this->hasOne(Metric::className(), ['id' => 'metric_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetricItem()
    {
        return $this->hasOne(MetricItem::className(), ['id' => 'metric_item_id']);
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
        return "/case-metric";
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
