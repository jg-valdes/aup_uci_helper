<?php

namespace backend\modules\v1\models;

use Yii;
use yii\helpers\Url;
use backend\models\business\Artifact;

class ArtifactModel extends Artifact
{
    public function fields()
    {
        return [
            'id' => function(Artifact $model){
                return $model->id;
            },
            'url' => function(Artifact $model){
                return Url::to(['/v1/artifact/view', 'id'=>$model->id]);
            },
            'name' => function(Artifact $model){
                return $model->name;
            },
            'description' => function(Artifact $model){
                return isset($model->description) || empty($model->description) ? $model->description : "";
            },
            'has_resource' => function(Artifact $model){
                return $model->hasResource();
            },
            'resource' => function(Artifact $model){
                if($model->hasResource()){
                    return Url::to(['/artifact/download', 'id'=>$model->id]);
                }else{
                    return "";
                }
            },
            'order' => function(Artifact $model){
                return $model->order;
            },
            'views' => function(Artifact $model){
                return $model->views;
            },
            'downloads' => function(Artifact $model){
                return $model->downloads;
            },
            'process' => function(Artifact $model){
                return $model->process->getModelAsJson();
            },
            'scenarios' => function(Artifact $model){
                return $model->getScenariosAsJson();
            },
            'roles' => function(Artifact $model){
                return $model->getRolesAsJson();
            },
            'created_at' => function(Artifact $model){
                return $model->created_at;
            },
            'updated_at' => function(Artifact $model){
                return $model->updated_at;
            },

        ];
    }
}