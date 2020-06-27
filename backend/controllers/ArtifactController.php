<?php

namespace backend\controllers;

use Yii;
use backend\models\business\Artifact;
use backend\models\business\ArtifactSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use yii\db\Exception;
use yii\web\Response;

/**
 * ArtifactController implements the CRUD actions for Artifact model.
 */
class ArtifactController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
     * Lists all Artifact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArtifactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Artifact model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Artifact model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Artifact(['status'=>Artifact::STATUS_ACTIVE]);

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                $resource = $model->uploadResource();

                if($model->save())
                {
                    if($resource){
                        $path = $model->getResourceFile();
                        $resource->saveAs($path);
                    }
                    $transaction->commit();

                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));

                    return $this->redirect(['index']);
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
        ]);

    }

    /**
     * Updates an existing Artifact model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
                                    Yii::info("Error deleting resource on Artifact: " . $old_resource_file);
                                    Yii::info($exception->getMessage());
                                }
                            }

                            $path = $model->getResourceFile();
                            $resource->saveAs($path);
                        }
                        $transaction->commit();

                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                        return $this->redirect(['index']);
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
        ]);

    }

    /**
     * Deletes an existing Artifact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

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

            return $this->redirect(['index']);
        }
        catch (Exception $e)
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
            $transaction->rollBack();
        }
    }

    /**
     * Finds the Artifact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Artifact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Artifact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
    * Bulk Deletes for existing Artifact models.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @return mixed
    */
    public function actionMultiple_delete()
    {
        if(Yii::$app->request->post('row_id'))
        {
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

            return $this->redirect(['index']);
        }
    }

    /**
     * Returns the last order Artifact for a selected Process
     * @param $processId integer Process to identify the last order Artifact
     * @return array
     */
    public function actionGetLastOrder($processId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->getIsAjax()) {
            $lastOrder = Artifact::getLastOrderForArtifact($processId);
            return [
                'status' => 200,
                'order' => $lastOrder + 1
            ];
        }

        return [
            'status' => 404,
            'message' => Yii::t("backend", "Página no encontrada.")
        ];
    }

    /**
     * Download an existing Resource model.
     * If download is successful, the downs counter is updated on DB.
     * @param integer $id
     * @param bool $fromView
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionDownload($id, $fromView = false)
    {
        $model = $this->findModel($id);
            $attachName = $model->process->name . "_" . str_replace(" ", "_", $model->name . "." . $model->getResourceExtension());

            set_time_limit(5 * 60);
            $route = $model->getResourceFile();
            $model->updateAttributes(['downloads'=>$model->downloads+1]);
            Yii::$app->response->sendFile($route, $attachName)->send();

            return $fromView ? $this->redirect(['view', 'id' => $id]) : $this->redirect(['index']);

    }
}
