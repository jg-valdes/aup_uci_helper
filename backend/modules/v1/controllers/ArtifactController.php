<?php

namespace backend\modules\v1\controllers;

use backend\models\business\Artifact;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


/**
 * Artifact controller for the `v1` module
 */
class ArtifactController extends ApiController
{
    public $modelClass = 'backend\modules\v1\models\ArtifactModel';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Remove credentials check for this controller
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete'], $actions['view']);

        return $actions;
    }

    /**
     * Returns Artifact
     * @param $id int Artifact ID
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $artifact = Artifact::findOne($id);
        if (isset($artifact)) {
            return [
                "statusCode" => 200,
                "success" => true,
                "message" => Yii::t("backend", "Artefacto encontrado"),
                "result" => $artifact->getModelAsJson()
            ];
        } else {
            throw new NotFoundHttpException(Yii::t("backend", "No se ha encontrado ningún Artefacto con ese identificador."));
        }
    }

}
