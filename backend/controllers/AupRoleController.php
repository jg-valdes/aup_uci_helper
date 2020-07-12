<?php

namespace backend\controllers;

use backend\models\business\RoleResponsibility;
use Yii;
use backend\models\business\AupRole;
use backend\models\business\AupRoleSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use yii\db\Exception;

/**
 * AupRoleController implements the CRUD actions for AupRole model.
 */
class AupRoleController extends Controller
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
     * Lists all AupRole models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AupRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AupRole model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $query = RoleResponsibility::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $query->andFilterWhere(['aup_role_id' => $model->id]);

        return $this->render('view', [
            'model' => $model,
            'modelResponsibility' => new RoleResponsibility(['status' => RoleResponsibility::STATUS_ACTIVE]),
            'responsibilityDataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new AupRole model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AupRole(['status' => AupRole::STATUS_ACTIVE]);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if ($model->save()) {
                    $transaction->commit();

                    GlobalFunctions::addFlashMessage('success', Yii::t('backend', 'Elemento creado correctamente'));

                    return $this->redirect(['index']);
                } else {
                    GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error creando el elemento'));
                }
            } catch (Exception $e) {
                GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error, ha ocurrido una excepción creando el elemento'));
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing AupRole model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (isset($model) && !empty($model)) {
            if ($model->load(Yii::$app->request->post())) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($model->save()) {
                        $transaction->commit();

                        GlobalFunctions::addFlashMessage('success', Yii::t('backend', 'Elemento actualizado correctamente'));

                        return $this->redirect(['view', 'id'=>$model->id]);
                    } else {
                        GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error actualizando el elemento'));
                    }
                } catch (Exception $e) {
                    GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error, ha ocurrido una excepción actualizando el elemento'));
                    $transaction->rollBack();
                }
            }
        } else {
            GlobalFunctions::addFlashMessage('warning', Yii::t('backend', 'El elemento buscado no existe'));
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing AupRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if ($model->delete()) {
                $transaction->commit();

                GlobalFunctions::addFlashMessage('success', Yii::t('backend', 'Elemento eliminado correctamente'));
            } else {
                GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error eliminando el elemento'));
            }

            return $this->redirect(['index']);
        } catch (Exception $e) {
            GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error, ha ocurrido una excepción eliminando el elemento'));
            $transaction->rollBack();
        }
    }

    /**
     * Finds the AupRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AupRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AupRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'La página solicitada no existe'));
        }
    }

    /**
     * Bulk Deletes for existing AupRole models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionMultiple_delete()
    {
        if (Yii::$app->request->post('row_id')) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $pk = Yii::$app->request->post('row_id');
                $count_elements = count($pk);

                $deleteOK = true;
                $nameErrorDelete = '';
                $contNameErrorDelete = 0;

                foreach ($pk as $key => $value) {
                    $model = $this->findModel($value);

                    if (!$model->delete()) {
                        $deleteOK = false;
                        $nameErrorDelete = $nameErrorDelete . '[' . $model->name . '] ';
                        $contNameErrorDelete++;
                    }
                }

                if ($deleteOK) {
                    if ($count_elements === 1) {
                        GlobalFunctions::addFlashMessage('success', Yii::t('backend', 'Elemento eliminado correctamente'));
                    } else {
                        GlobalFunctions::addFlashMessage('success', Yii::t('backend', 'Elementos eliminados correctamente'));
                    }

                    $transaction->commit();
                } else {
                    if ($count_elements === 1) {
                        if ($contNameErrorDelete === 1) {
                            GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error eliminando el elemento') . ': <b>' . $nameErrorDelete . '</b>');
                        }
                    } else {
                        if ($contNameErrorDelete === 1) {
                            GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error eliminando el elemento') . ': <b>' . $nameErrorDelete . '</b>');
                        } elseif ($contNameErrorDelete > 1) {
                            GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error eliminando los elementos') . ': <b>' . $nameErrorDelete . '</b>');
                        }
                    }
                }
            } catch (Exception $e) {
                GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error, ha ocurrido una excepción eliminando el elemento'));
                $transaction->rollBack();
            }

            return $this->redirect(['index']);
        }
    }

    /**
     * Creates a new RoleResponsibility model using ajax request.
     * If creation is successful, the response will tell it using json format.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreateResponsibilityAjax($id)
    {
        $aupRole = $this->findModel($id);

        $model = new RoleResponsibility([
            'status' => RoleResponsibility::STATUS_ACTIVE,
        ]);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($model->load(Yii::$app->request->post())) {
                // process upload resource file instance
                $model->aup_role_id = $aupRole->id;
                if ($model->save()) {
                    return [
                        'data' => [
                            'success' => true,
                            'model' => $model,
                            'message' => 'Model has been saved.',
                        ],
                        'code' => 0,
                    ];
                }
            }

            return [
                'data' => [
                    'success' => false,
                    'model' => $model,
                    'errors' => $model->getErrors(),
                    'message' => 'An error ocurred.',
                ],
                'code' => 1,
            ];

        }
    }

    /**
     * Ajax Update RoleResponsibility
     * @throws NotFoundHttpException
     */
    public function actionUpdateResponsibility()
    {
        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            // instantiate your landing page model for saving
            $id = Yii::$app->request->post('editableKey');

            $model = $this->findRoleResponsibilityModel($id);
            $posted = current($_POST['RoleResponsibility']);
            $post = ['RoleResponsibility' => $posted];

            if ($model->load($post) && $model->save()) {
                $out = ['output' => '', 'message' => ''];
            } else {
                $out = ['output' => "error", 'message' => $model->getFirstErrors()];
            }
            // return ajax json encoded response and exit

            echo json_encode($out);
            return;
        }
    }

    /**
     * Deletes an existing RoleResponsibility model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDeleteResponsibility($id)
    {
        $model = $this->findRoleResponsibilityModel($id);
        $backId = $model->aup_role_id;

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($model->delete()) {
                $transaction->commit();

                GlobalFunctions::addFlashMessage('success', Yii::t('backend', 'Elemento eliminado correctamente'));
            } else {
                GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error eliminando el elemento'));
            }
        } catch (Exception $e) {
            GlobalFunctions::addFlashMessage('danger', Yii::t('backend', 'Error, ha ocurrido una excepción eliminando el elemento'));
            $transaction->rollBack();
        }

        return $this->redirect(['view', 'id' => $backId]);

    }

    /**
     * Finds the RoleResponsibility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoleResponsibility the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findRoleResponsibilityModel($id)
    {
        if (($model = RoleResponsibility::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'La página solicitada no existe'));
        }
    }

}
