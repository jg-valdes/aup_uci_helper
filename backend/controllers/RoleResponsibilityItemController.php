<?php

namespace backend\controllers;

use backend\models\business\Artifact;
use backend\models\business\ArtifactResponsibilityItem;
use backend\models\business\RoleResponsibility;
use Yii;
use backend\models\business\RoleResponsibilityItem;
use backend\models\business\RoleResponsibilityItemSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use yii\db\Exception;

/**
 * RoleResponsibilityItemController implements the CRUD actions for RoleResponsibilityItem model.
 */
class RoleResponsibilityItemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['download'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'multiple_delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RoleResponsibilityItem models.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($id)
    {
        $responsibility = $this->findResponsibilityModel($id);
        $searchModel = new RoleResponsibilityItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $responsibility->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'responsibility' => $responsibility
        ]);
    }

    /**
     * Displays a single RoleResponsibilityItem model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new RoleResponsibilityItem model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($id)
    {
        $responsibility = $this->findResponsibilityModel($id);
        $model = new RoleResponsibilityItem(['status'=>RoleResponsibilityItem::STATUS_ACTIVE, 'role_responsibility_id'=>$responsibility->id]);

        $artifacts_selected = [];
        $list_artifacts = Artifact::getSelectMap();

        $items_artifacts = [];
        foreach ($list_artifacts AS $key => $value)
        {
            $items_artifacts[$key] = [
                'content' => $value,
                'options' => ['data' => ['id'=>$key]],
            ];
        }

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                $resource = $model->uploadResource();
                $artifacts = explode(',',$model->artifacts);
                if($model->save())
                {
                    if($resource){
                        $path = $model->getResourceFile();
                        $resource->saveAs($path);
                    }
                    foreach ($artifacts AS $index => $artifactId)
                    {
                        ArtifactResponsibilityItem::addRelation($model->id, $artifactId);
                    }

                    $transaction->commit();

                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));

                    return $this->redirect(['index', 'id' => $id]);
                }
                else
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el elemento'));
                }
            }
            catch (Exception $e)
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción creando el elemento'));
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'items_selected' => $artifacts_selected,
            'items_artifacts' => $items_artifacts,
        ]);

    }

    /**
     * Updates an existing RoleResponsibilityItem model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $list_artifacts_selected = ArtifactResponsibilityItem::getRelationsMapForRoleResponsibilityItem($id);
        $list_artifacts = Artifact::getSelectMap();
        $list_artifacts = array_diff($list_artifacts, $list_artifacts_selected);

        $items_artifacts = [];
        foreach ($list_artifacts AS $key => $value)
        {
            $items_artifacts[$key] = [
                'content' => $value,
                'options' => ['data' => ['id'=>$key]],
            ];
        }

        $artifacts_selected = [];
        $old_selected = [];

        foreach ($list_artifacts_selected AS $key => $value)
        {
            $old_selected[] = "{$key}";

            $artifacts_selected[$key] = [
                'content' => $value,
                'options' => ['data' => ['id'=>$key]],
            ];
        }

        if(isset($model) && !empty($model))
        {
            $old_resource_file = $model->getResourceFile();
            $old_resource = $model->filename;

            if ($model->load(Yii::$app->request->post()))
            {
                $transaction = \Yii::$app->db->beginTransaction();

                try
                {
                    $resource = $model->uploadResource();

                    // revert back if no valid file instance uploaded
                    if ($resource === false) {
                        $model->filename = $old_resource;
                    }
                    $artifacts = explode(',',$model->artifacts);

                    if($model->save())
                    {
                        // upload only if valid uploaded file instance found by main logo
                        if ($resource !== false) // delete old and overwrite
                        {
                            if(file_exists($old_resource_file))
                            {
                                try{
                                    unlink($old_resource_file);
                                }catch (\Exception $exception){
                                    Yii::info("Error deleting resource on RoleResponsibilityItem: " . $old_resource_file);
                                    Yii::info($exception->getMessage());
                                }
                            }

                            $path = $model->getResourceFile();
                            $resource->saveAs($path);
                        }

                        $toRemove = array_diff($old_selected, $artifacts);
                        if(isset($toRemove) && !empty($toRemove))
                        {
                            foreach ($toRemove as $item)
                            {
                                ArtifactResponsibilityItem::deleteRelation($model->id, $item);
                            }
                        }

                        if(isset($artifacts) && !empty($artifacts)){
                            foreach ($artifacts AS $index => $item)
                            {
                                ArtifactResponsibilityItem::addRelation($model->id, $item);
                            }
                        }
                        $transaction->commit();

                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                        return $this->redirect(['view', 'id'=>$model->id]);
                    }
                    else
                    {
                        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando el elemento'));
                    }
                }
                catch (Exception $e)
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción actualizando el elemento'));
                    $transaction->rollBack();
                }
            }
        }
        else
        {
            GlobalFunctions::addFlashMessage('warning',Yii::t('backend','El elemento buscado no existe'));
        }

        return $this->render('update', [
            'model' => $model,
            'items_selected' => $artifacts_selected,
            'items_artifacts' => $items_artifacts,
        ]);

    }

    /**
     * Deletes an existing RoleResponsibilityItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $backID = $model->role_responsibility_id;

        $transaction = \Yii::$app->db->beginTransaction();

        try
        {
            if($model->delete())
            {
                $model->deleteResource();
                $transaction->commit();

                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento'));
            }

            return $this->redirect(['index', 'id'=>$backID]);
        }
        catch (Exception $e)
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
            $transaction->rollBack();
        }
    }

    /**
     * Finds the RoleResponsibilityItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoleResponsibilityItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RoleResponsibilityItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
     * Finds the RoleResponsibility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoleResponsibility the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findResponsibilityModel($id)
    {
        if (($model = RoleResponsibility::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
     * Bulk Deletes for existing RoleResponsibilityItem models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionMultiple_delete()
    {
        if(Yii::$app->request->post('row_id'))
        {
            $backID = null;
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                $pk = Yii::$app->request->post('row_id');
                $count_elements = count($pk);

                $deleteOK = true;
                $nameErrorDelete = '';
                $contNameErrorDelete = 0;

                foreach ($pk as $key => $value)
                {
                    $model= $this->findModel($value);
                    $backID = $model->role_responsibility_id;
                    if(!$model->delete())
                    {
                        $deleteOK=false;
                        $nameErrorDelete= $nameErrorDelete.'['.$model->name.'] ';
                        $contNameErrorDelete++;
                    } else {
                        $model->deleteResource();
                    }
                }

                if($deleteOK)
                {
                    if($count_elements === 1)
                    {
                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
                    }
                    else
                    {
                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elementos eliminados correctamente'));
                    }

                    $transaction->commit();
                }
                else
                {
                    if($count_elements === 1)
                    {
                        if($contNameErrorDelete===1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento').': <b>'.$nameErrorDelete.'</b>');
                        }
                    }
                    else
                    {
                        if($contNameErrorDelete===1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento').': <b>'.$nameErrorDelete.'</b>');
                        }
                        elseif($contNameErrorDelete>1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando los elementos').': <b>'.$nameErrorDelete.'</b>');
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
                $transaction->rollBack();
            }

            return $this->redirect(['index', 'id'=>$backID]);
        }
    }

    /**
     * Download an existing Resource model.
     * If download is successful, the downs counter is updated on DB.
     * @param integer $id
     * @param bool $fromView
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDownload($id, $fromView = false)
    {
        $model = $this->findModel($id);
        $attachName = str_replace(" ", "_", $model->roleResponsibility->name . "_" . $model->name) . "." . $model->getResourceExtension();

        set_time_limit(5 * 60);
        $route = $model->getResourceFile();
        $model->updateAttributes(['downloads'=>$model->downloads+1]);
        Yii::$app->response->sendFile($route, $attachName)->send();

        return $fromView ? $this->redirect(['view', 'id' => $id]) : $this->redirect(['index']);
    }

}
