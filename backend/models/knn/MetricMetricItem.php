<?php

namespace backend\models\knn;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%metric_metric_item}}".
 *
 * @property int $id
 * @property int $metric_id
 * @property int $metric_item_id
 * @property double $weight
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Metric $metric
 * @property MetricItem $metricItem

 */
class MetricMetricItem extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%metric_metric_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metric_id', 'metric_item_id'], 'required'],
            [['metric_id', 'metric_item_id', 'status'], 'integer'],
            [['weight'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'metric_item_id' => Yii::t('backend', 'Metric Item ID'),
            'weight' => Yii::t('backend', 'Weight'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
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
        return "/metric-metric-item";
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
