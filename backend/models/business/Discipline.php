<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%discipline}}".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property int $views
 * @property int $order
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Process[] $processes

 */
class Discipline extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%discipline}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'order'], 'required'],
            [['description'], 'string'],
            [['order', 'status', 'views'], 'integer'],
            [['order'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['name', 'description', 'alias', 'order', 'status'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'alias' => Yii::t('backend', 'Alias'),
            'description' => Yii::t('backend', 'Descripción'),
            'views' => Yii::t('backend', 'Visitas'),
            'order' => Yii::t('backend', 'Órden'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcesses()
    {
        return $this->hasMany(Process::className(), ['discipline_id' => 'id']);
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
        return "/discipline";
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

    public function getAlias()
    {
        if(isset($this->alias) && !empty($this->alias)){
            return $this->alias;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getModelAsJson($includeProcess = false, $includeArtifacts = false)
    {
        $json = [
            'id' => $this->id,
            'name' => $this->name,
            'alias' => isset($this->alias) && !empty($this->alias)? $this->alias : "",
            'description' => $this->description,
            'views' => GlobalFunctions::getFormattedViewsCount($this->views),
            'order' => $this->order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        if($includeProcess){
            $processes = [];
            foreach ($this->processes as $process){
                array_push($processes, $process->getModelAsJson($includeArtifacts));
            }
            $json['processes'] = $processes;
        }
        return $json;
    }
}
