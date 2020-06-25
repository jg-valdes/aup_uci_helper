<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%process}}".
 *
 * @property int $id
 * @property int $discipline_id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property int $order
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Artifact[] $artifacts
 * @property Discipline $discipline

 */
class Process extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%process}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discipline_id', 'name', 'order'], 'required'],
            [['discipline_id', 'order', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['order'], 'unique', 'targetAttribute'=>['order', 'discipline_id']],
            [['discipline_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discipline::className(), 'targetAttribute' => ['discipline_id' => 'id']],
            [['name', 'description', 'alias', 'order', 'discipline_id', 'status'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'discipline_id' => Yii::t('backend', 'Disciplina'),
            'name' => Yii::t('backend', 'Nombre'),
            'alias' => Yii::t('backend', 'Alias'),
            'description' => Yii::t('backend', 'Descripción'),
            'order' => Yii::t('backend', 'Órden'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtifacts()
    {
        return $this->hasMany(Artifact::className(), ['process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscipline()
    {
        return $this->hasOne(Discipline::className(), ['id' => 'discipline_id']);
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
        return "/process";
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
     * Returns the las order of any process related with Discipline id param
     * @param $disciplineId integer Discipline ID
     * @return mixed
     */
    public static function getLastOrderForDiscipline($disciplineId)
    {
        return self::find()->joinWith(['discipline'])->where(['discipline_id' => $disciplineId])->max('process.order');
    }

    /**
     * Returns a formatted link for related discipline or a no value span message
     * @return string
     */
    public function getDisciplineLink()
    {
        if(isset($this->discipline)){
            return $this->discipline->getIDLinkForThisModel();
        }
        return GlobalFunctions::getNoValueSpan();
    }

    /**
     * Returns alias value or a no value span message
     * @return string
     */
    public function getAlias()
    {
        if(isset($this->alias) && !empty($this->alias)){
            return $this->alias;
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
