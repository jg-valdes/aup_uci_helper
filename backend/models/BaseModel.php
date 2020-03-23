<?php

namespace backend\models;

use common\models\GlobalFunctions;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use Yii;

class BaseModel extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "1";
    const STATUS_INACTIVE = "0";

    /**
     * Save create and update times
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
        }

        return parent::beforeSave($insert); //
    }



    /** :::::::::::: Start > Util Gets ::::::::::::*/

    /**
     * @return string The base name for current model, it must be implemented on each child
     */
    public function getBaseName()
    {
        return StringHelper::basename(get_class($this));
    }
    /**
     * @return bool true if current model has active status
     */
    public function isActive(){
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * Return a formatted span with status value
     * @param boolean $show_yes_no true if shoe YES|NO answer
     * @return string
     */
    public function getStatusLabel($show_yes_no = NULL){

        return GlobalFunctions::getStatusValue($this->status, $show_yes_no);
    }

    /**
     * @return string base route to model links, default to '/'
     */
    public function getBaseLink(){
        return '/';
    }

    /**
     * Return the model ID
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a link that represents current object model
     * @return string
     */
    public function getIDLinkForThisModel()
    {
        if (isset($this->id)) {
            return Html::a($this->name, [$this->getBaseLink() . "/view", 'id' => $this->getId()]);
        } else {
            return "<span class='label label-danger'>No definido</span>";
        }
    }

    /**
     * Returns a mapped array for using on Select widget
     *
     * @param boolean $check_status
     * @return array
     */
    public static function getSelectMap($check_status=true)
    {
        $query = self::find();
        if($check_status)
        {
            $query->where(['status' => self::STATUS_ACTIVE]);
        }

        $models = $query->asArray()->all();

        $results = ( count( $models ) === 0 ) ? [ '' => '' ] : ArrayHelper::map($models, "id", "name");

        return $results;
    }

    /** :::::::::::: END > Util Gets ::::::::::::*/
}