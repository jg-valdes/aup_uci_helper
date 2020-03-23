<?php
namespace backend\controllers;

use backend\models\business\Shipping;
use Yii;
use yii\helpers\FileHelper;
use common\controllers\BaseController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'ckeditorupload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = "main-clean";

        return $this->render('index', [

        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Action for upload images to server when using CKEditor widget
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCkeditorupload()
    {
        $funcNum = $_REQUEST['CKEditorFuncNum'];

        if ($_FILES['upload']) {

            if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
                $message = Yii::t('app', "Por favor, suba alguna imagen");
            } else if ($_FILES['upload']["size"] == 0 OR $_FILES['upload']["size"] > 5 * 1024 * 1024) {
                $message = Yii::t("app","El tamaño de la imagen no debe exceder los ") . " 5MB";
            } else if (($_FILES['upload']["type"] != "image/jpg")
                AND ($_FILES['upload']["type"] != "image/jpeg")
                AND ($_FILES['upload']["type"] != "image/png")) {
                $message = Yii::t("app","Ha ocurrido un error subiendo la imagen, por favor intente de nuevo");
            } else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {

                $message = Yii::t("app","Formato de imagen no permitido, debe ser JPG, JPEG o PNG.");
            } else {

                $extension = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);

                //Rename the image here the way you want
                $name = "CKE_" . time() . '.' . $extension;

                // Here is the folder where you will save the images
                $folder = '/uploads/ckeditor_images/';
                $realPath = Yii::$app->getBasePath() . "/web" . $folder;
                if (!file_exists($realPath)) {
                    FileHelper::createDirectory($realPath, 0777);
                }

                $url = Yii::$app->urlManager->getBaseUrl() . $folder . $name;

                move_uploaded_file($_FILES['upload']['tmp_name'], $realPath . $name);
                $message = Yii::t("app","Imagen subida satisfactoriamente");
                //Giving permission to read and modify uploaded image
                chmod($realPath . $name, 0777);
            }

            echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'
                . $funcNum . '", "' . $url . '", "' . $message . '" );</script>';

        }
    }
}
