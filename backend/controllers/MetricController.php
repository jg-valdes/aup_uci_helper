<?php

namespace backend\controllers;

use backend\models\knn\MetricItem;
use backend\models\knn\MetricMetricItem;
use Yii;
use backend\models\knn\Metric;
use backend\models\knn\MetricSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use yii\db\Exception;

/**
 * MetricController implements the CRUD actions for Metric model.
 */
class MetricController extends Controller
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
     * Lists all Metric models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MetricSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Metric model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $query = MetricMetricItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $query->andFilterWhere(['metric_id' => $model->id]);

        return $this->render('view', [
            'model' => $model,
            'modelItem' => new MetricItem(['status'=>MetricItem::STATUS_ACTIVE]),
            'modelRelation' => new MetricMetricItem(['status'=>MetricMetricItem::STATUS_ACTIVE]),
            'metricItemsDataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Metric model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Metric(['status'=>Metric::STATUS_ACTIVE]);

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                if($model->save())
                {
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
     * Updates an existing Metric model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(isset($model) && !empty($model))
        {
            if ($model->load(Yii::$app->request->post()))
            {
                $transaction = \Yii::$app->db->beginTransaction();

                try
                {
                    if($model->save())
                    {
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
        ]);

    }

    /**
     * Deletes an existing Metric model.
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
     * Finds the Metric model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Metric the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Metric::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
    * Bulk Deletes for existing Metric models.
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
     * Creates a new MetricItem model using ajax request.
     * If creation is successful, the response will tell it using json format.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreateItemAjax($id)
    {
        $metric = $this->findModel($id);

        $item = new MetricItem([
            'status' => MetricItem::STATUS_ACTIVE,
        ]);
        $relation = new MetricMetricItem([
            'status' => MetricMetricItem::STATUS_ACTIVE,
            'weight' => 0,
        ]);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($item->load(Yii::$app->request->post()) && $relation->load(Yii::$app->request->post())) {
                // process upload resource file instance
                if($item->save()){
                    $relation->metric_id = $metric->id;
                    $relation->metric_item_id = $item->id;
                    if($relation->save()){
                        return [
                            'data' => [
                                'success' => true,
                                'model' => $item,
                                'message' => 'Model has been saved.',
                            ],
                            'code' => 0,
                        ];
                    }else{
                        $item->delete();
                    }
                }
            }

            return [
                'data' => [
                    'success' => false,
                    'model' => $item,
                    'errors' => array_merge($item->getErrors(), $relation->getErrors()),
                    'message' => 'An error ocurred.',
                ],
                'code' => 1,
            ];

        }
    }

    /**
     * Ajax Update Metric Item
     * @throws NotFoundHttpException
     */
    public function actionUpdateItem()
    {
        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            // instantiate your landing page model for saving
            $relationId = Yii::$app->request->post('editableKey');
            $attr = Yii::$app->request->post('editableAttribute');

            $model = $this->findRelationModel($relationId);
            $item = $model->metricItem;
            $posted = current($_POST['MetricMetricItem']);
            //$post = ['MetricMetricItem' => $posted];

            switch ($attr){
                case 'weight':
                    $model->weight = $posted['weight'];
                    break;
                case 'metric_item_id':
                    $item->name = $posted['metric_item_id'];
                    $model->metric_item_id = $item->id;
                    break;
                case 'status':
                    $item->status = $posted['status'];
                    $model->status = $posted['status'];
                    break;
            }

            if($model->save() && $item->save()){
                $out = ['output' => '', 'message' => ''];
            }else{
                $err = $item->getFirstErrors();
                $map = [];
                foreach ($err as $key=>$value){
                    $map["metric-{$key}"] = $value;
                }
                $errors = array_merge($model->getFirstErrors(), $map);
                $out = ['output' => "error", 'message' => $errors];
            }
            // return ajax json encoded response and exit

            echo json_encode($out);
            return;
        }
    }

    /**
     * Deletes an existing MetricItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDeleteItem($id)
    {
        $model = $this->findRelationModel($id);
        $backId = $model->metric_id;

        $transaction = \Yii::$app->db->beginTransaction();
        try
        {
            if($model->metricItem->delete())
            {
                $transaction->commit();

                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento'));
            }
        }
        catch (Exception $e)
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
            $transaction->rollBack();
        }

        return $this->redirect(['view', 'id'=>$backId]);

    }

    /**
     * Finds the MetricMetricItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MetricMetricItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findRelationModel($id)
    {
        if (($model = MetricMetricItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }
}
