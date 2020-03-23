<?php

namespace backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\controllers\BaseController;
use backend\models\settings\SystemConfig;
use backend\models\settings\SystemConfigSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SystemConfigController implements the CRUD actions for SystemConfig model.
 */
class SystemConfigController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-selected' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SystemConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SystemConfig model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SystemConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SystemConfig([
            'status'=>SystemConfig::STATUS_ACTIVE,
            'description'=>Yii::t("app", "Configuración del sistema") . " ...",
            "json_config"=> "{\n    \"attr1\": \"value1\"\n}"
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SystemConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SystemConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SystemConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemConfig::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Delete an object via ajax
     * @param $id integer Model ID to delete
     * @return string response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionStatus($id)
    {
        if (Yii::$app->request->getIsAjax()) {

            if (($model = $this->findModel($id)) != null) {
                Yii::$app->db->createCommand()
                    ->update(SystemConfig::tableName(), [
                        'status' => $model->isActive() ? SystemConfig::STATUS_INACTIVE : SystemConfig::STATUS_ACTIVE
                    ], ['id' => $id])->execute();
            }
            return "OK";
        }

        return "ERROR";
    }

    /**
     * Delete multiple record selected via GridView
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteSelected()
    {
        $ids = Yii::$app->request->post('selection');
        if(isset($ids))
        {
            foreach ($ids as $id){
                $this->findModel($id)->delete();
            }
        }
        return "200 OK";
    }

    /**
     * Returns the Setting Params according to default constants of SystemConfig
     * @param $setting_name string Setting name for search in defaults
     * @return array
     */
    public function actionGet_setting_params($setting_name)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(isset($setting_name) && !empty($setting_name) && ArrayHelper::keyExists($setting_name, SystemConfig::getSettingsConfigParamsMap())){
            if(Yii::$app->request->getIsAjax()){

                return [
                    'status' => '200',
                    'params' => SystemConfig::getSettingsConfigParamsMap()[$setting_name]
                ];
            }
        }

        return [
            'status' => '404',
            'message' => "Setting not found"
        ];
    }
}
