<?php

namespace backend\controllers;

use backend\models\auth\ProfileChangeOwnPasswordForm;
use backend\models\business\UserCoupon;
use webvimark\modules\UserManagement\models\forms\LoginForm;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\HtmlPurifier;
use common\controllers\BaseController;
use Yii;
use backend\models\auth\UserProfile;
use backend\models\auth\UserProfileSearch;
use yii\helpers\FileHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * UserProfileController implements the CRUD actions for UserProfile model.
 */
class UserProfileController extends BaseController
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
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin($next=NULL)
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $loginError = false;
        if ($model->load(Yii::$app->request->post())) {
            if($model->login()){
                $next = HtmlPurifier::process($next);
                if(!isset($next) || empty($next)){
                    $next = Yii::$app->request->getReferrer();
                }

                return $this->redirect($next);
            }else{
                $loginError = true;
            }

        }

        $model->password = "";

        return $this->render('login', [
            'model' => $model,
            'loginError' => $loginError
        ]);

    }

    /**
     * Render a profile view, if user is superadmin then redirect to index user-profiles
     * @param $id
     * @return string|Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionProfile($id)
    {
        if($id==1){
              return $this->redirect('index');
        }

        $model = UserProfile::findOne(['user_id'=>$id]);

        $model->scenario = User::SCENARIO_DEFAULT;
        $model->username = $model->user->username;
        $model->email = $model->user->email;
        $model->status = $model->user->status;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->save()) {
                    if ($this->manageFiles($model)) {
                        Yii::$app->session->setFlash('success', "¡Datos actualizados correctamente!");
                        return $this->redirect(['profile', 'id' => $model->user_id]);
                    }
                }

        }

        return $this->render('profile', ['model'=>$model]);
    }

    public function actionChangeOwnPassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::getCurrentUser();

        if ($user->status != User::STATUS_ACTIVE) {
            throw new ForbiddenHttpException();
        }

        $model = new ProfileChangeOwnPasswordForm(['user' => $user, 'scenario' => 'changePassword']);


        if (Yii::$app->request->isAjax AND $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) AND $model->changePassword()) {
            return $this->render('changeOwnPasswordSuccess');
        }

        return $this->render('changeOwnPassword', compact('model'));
    }

    /**
     * Remove a user profile avatar via ajax
     * @param integer $id Identity for UserProfile model to remove image
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionRemoveImage($id = null)
    {
        if (isset($id)) {
            if (($model = $this->findModel($id)) != null) {
                if ($model->hasAvatar()) {
                    $model->removeAvatar();
                    Yii::$app->db->createCommand()
                        ->update(UserProfile::tableName(), [
                            'avatar' => null
                        ], ['id' => $model->id])->execute();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Render ajax or usual depends on request
     *
     * @param string $view
     * @param array $params
     *
     * @return string|\yii\web\Response
     */
    protected function renderIsAjax($view, $params = [])
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($view, $params);
        } else {
            return $this->render($view, $params);
        }
    }

    /**
     * Displays a single UserProfile model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewParent($id)
    {
        return $this->render('view', [
            'model' => UserProfile::findOne(['user_id' => $id]),
        ]);
    }

    /**
     * Redirect to change password view.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionChangePassword($id)
    {
        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException('Usuario no encontrado');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post())) {
            $user = $model->user;
            $user->scenario = 'changePassword';
            $user->password = $model->password;
            $user->repeat_password = $model->repeat_password;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', "Contraseña ha sido cambiada.");
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $model->addErrors($user->getFirstErrors());
            }

        }

        return $this->render('changePassword', ['model' => $model]);
    }

    /**
     * Lists all UserProfile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserProfile model.
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
     * Creates a new UserProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserProfile(['scenario' => 'newUser', 'status'=>UserProfile::STATUS_ACTIVE]);

        if ($model->load(Yii::$app->request->post()) && $model->validateNewUser()) {
            if (($roles = Yii::$app->request->post('roles', [])) != []) {
                $coupons = $_POST['UserProfile']['coupons'];
                if ($model->save()) {
                    $this->assignRoles($model->user->id);
                    UserCoupon::manageUserCouponsRelationship($model->user_id, $coupons);
                    if ($this->manageFiles($model)) {
                        Yii::$app->session->setFlash('success', "¡Datos creados correctamente!");
                        return $this->redirect(['index']);
                    }
                }
            } else {
                Yii::$app->session->setFlash('danger', "El usuario necesita tener algún rol asignado.");
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->username = $model->user->username;
        $model->email = $model->user->email;
        $model->status = $model->user->status;

        if ($model->load(Yii::$app->request->post()) && $model->validateNewUser()) {
            if (($roles = Yii::$app->request->post('roles', [])) != []) {
                $coupons = $_POST['UserProfile']['coupons'];
                if ($model->save()) {
                    $this->assignRoles($model->user->id);
                    UserCoupon::manageUserCouponsRelationship($model->user_id, $coupons);
                    if ($this->manageFiles($model)) {
                        Yii::$app->session->setFlash('success', "¡Datos actualizados correctamente!");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }

                }
            } else {
                Yii::$app->session->setFlash('danger', "El usuario necesita tener algún rol asignado.");
            }

        }

        $model->coupons = UserCoupon::getCouponIDsForUser($model->user_id);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserProfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        $parent = $user->user;
        $user->delete(); // Remove avatar profile
        $parent->delete();

        Yii::$app->session->setFlash('success', "¡Datos eliminados correctamente!");
        return $this->redirect(['index']);
    }

    /**
     * Finds the UserProfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserProfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserProfile::findOne($id)) !== null) {
            $model->username = $model->user->username;
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /** Allow to handle uploaded avatar for specific UserProfile
     * @param UserProfile $model
     * @return bool|int
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    protected function manageFiles(UserProfile $model)
    {
        $route = Yii::$app->getBasePath() . "/web/uploads/user_profiles/UserProfile_" . $model->id . "/";
        if (!file_exists($route)) {
            FileHelper::createDirectory($route, 0777);
        }

        if (isset($model->file)) {
            if (isset($model->avatar) && file_exists($route . $model->avatar)) {
                if(!is_dir($route . $model->avatar)){
                    unlink($route . $model->avatar);
                }
            }
            $model->avatar = "Avatar_" . time() . "." . $model->file->getExtension();
            $model->file->saveAs($route . $model->avatar);
            return Yii::$app->db->createCommand()
                ->update(UserProfile::tableName(), ['avatar' => $model->avatar], ['id' => $model->id])->execute();
        }

        return true;
    }

    /**Change Status
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionStatus($id)
    {
        $answer = "OK\n";
        if (Yii::$app->request->getIsAjax()) {
            if (($model = $this->findModel($id)) != null) {
                $answer .= "Load User Profile model \n";
                if (($user = User::findOne($model->user_id)) != null) {
                    $answer .= "Load User model \n";
                    Yii::$app->db->createCommand()
                        ->update(User::tableName(), [
                            'status' => ($user->status === User::STATUS_ACTIVE) ?
                                User::STATUS_INACTIVE : User::STATUS_ACTIVE
                        ], ['id' => $model->user_id])->execute();
                }
            }
            return $answer;
        }

        return "ERROR";
    }

    /** Returns the email of a selected user
     * @return string
     */
    public function actionAjaxEmail()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_RAW;

            $user_id = Yii::$app->request->post('user', null);
            if (isset($user_id)) {
                $email = User::findOne($user_id);
                if (isset($email)) {
                    return isset($email->email) ? $email->email : "";
                }
            }

            return "";
        }
    }

    /** Allow to manage roles assignment for User from profile view
     * @param $id integer The User parent ID
     */
    private function assignRoles($id)
    {
        $oldAssignments = array_keys(Role::getUserRoles($id));

        // To be sure that user didn't attempt to assign himself some unavailable roles
        $newAssignments = array_intersect(Role::getAvailableRoles(true, true), (array)Yii::$app->request->post('roles', []));

        $toAssign = array_diff($newAssignments, $oldAssignments);
        $toRevoke = array_diff($oldAssignments, $newAssignments);

        foreach ($toRevoke as $role) {
            User::revokeRole($id, $role);
        }

        foreach ($toAssign as $role) {
            User::assignRole($id, $role);
        }
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
                $user = $this->findModel($id);
                $parent = $user->user;
                $user->delete(); // Remove avatar profile
                $parent->delete();
            }
        }
        return "200 OK";
    }
}
