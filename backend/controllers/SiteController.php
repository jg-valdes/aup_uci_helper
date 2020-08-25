<?php
namespace backend\controllers;

use backend\models\business\Artifact;
use backend\models\business\Discipline;
use backend\models\business\Process;
use backend\models\business\Scenario;
use backend\models\business\ScenarioArtifact;
use backend\models\knn\CaseMetric;
use backend\models\knn\IaCase;
use backend\models\knn\Metric;
use backend\models\knn\MetricItem;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Cookie;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout', 'index',
                            'predictor', 'error','change_lang', "ckeditorupload",'info_test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'predictor', 'error','change_lang','info_test'],
                        'allow' => true,
                        'roles' => ['?'],
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

    public function actionIndex()
    {
        $scenarios = Scenario::find()->where(['status'=>Scenario::STATUS_ACTIVE])->orderBy('name')->all();
        $disciplines = Discipline::find()->where(['status'=>Discipline::STATUS_ACTIVE])->orderBy('order')->all();
        $tree = [];

        foreach ($scenarios as $scenario){
            $treeChild=[];
            $treeChild['scenario'] = ['id'=>$scenario->id, 'name' => $scenario->name, 'description'=>$scenario->description];
            $scenarioDisciplines = [];
            foreach ($disciplines as $discipline){
                $scenarioDiscipline = ['id'=>$discipline->id, 'name'=>$discipline->name, 'description'=> $discipline->description];

                // Search for Discipline -> Process
                $disciplineProcesses = [];
                $processes = $discipline->getProcesses()->where(['status'=>Process::STATUS_ACTIVE])->orderBy('order')->all();
                foreach ($processes as $process){
                    $disciplineProcess = ['id'=>$process->id, 'name'=>$process->name, 'description'=> $process->description];

                    // Search for Process -> Artifact (using Scenario)
                    $processArtifacts = [];
                    $artifacts = $process->getArtifacts()->where(['status'=>Artifact::STATUS_ACTIVE])->orderBy('order')->all();
                    foreach ($artifacts as $artifact){
                        if(ScenarioArtifact::existRelation($scenario->id, $artifact->id)){
                            $processArtifact = [
                                'id'=>$artifact->id,
                                'api_url' => Url::to(['/v1/artifact/view', 'id'=>$artifact->id]),
                                'name'=>$artifact->name,
                                'description'=> $artifact->description
                            ];
                            array_push($processArtifacts, $processArtifact);
                        }
                    }

                    $disciplineProcess['artifacts'] = $processArtifacts;
                    array_push($disciplineProcesses, $disciplineProcess);
                }
                $scenarioDiscipline['processes'] = $disciplineProcesses;
                array_push($scenarioDisciplines, $scenarioDiscipline);
            }

            $treeChild['scenario']['disciplines'] = $scenarioDisciplines;
            array_push($tree, $treeChild);
        }
        //var_dump($tree);exit(0);

        return $this->render('index', ['tree'=>$tree]);
    }

    public function actionPredictor()
    {
        $knn = [];
        $k = 5;
        $model = new IaCase(['status'=>IaCase::STATUS_ACTIVE]);

        if(Yii::$app->request->isPost){
            $formItems = Yii::$app->request->post();
            $k = Yii::$app->request->post('k_delimiter', $k);
            $model->save();
            foreach ($formItems as $key=>$value){
                $metricId = explode("_", $key);
                if(is_numeric($metricId[1])){
                    $metric = Metric::find()->where(['id'=>$metricId[1]])->one();
                    $item = MetricItem::find()->where(['id'=>$value])->one();
                    if( isset($metric) && isset($item)){
                        (new CaseMetric([
                            'ia_case_id' => $model->id,
                            'metric_id' => $metric->id,
                            'metric_item_id' => $item->id
                        ]))->save();
                    }
                }
            }

            $model->fillMetricsForCalculateDistance();

            $distances = [];
            foreach (IaCase::findAll(['status'=>IaCase::STATUS_ACTIVE]) as $case){
                array_push($distances, [
                    'distance'=>$model->calculateDistance($case),
                    'scenario'=> isset($case->scenario_id)? $case->scenario_id: 0
                ]);
            }


            usort($distances, function ($a, $b){
                if ($a['distance'] == $b['distance']) {
                    return 0;
                }
                return ($a['distance'] < $b['distance']) ? 1 : -1;
            });
            $results = array_slice($distances, 0, $k);

            foreach (Scenario::getSelectMap() as $key=>$value){
                $count = 0;
                foreach ($results as $item){
                    if($item['scenario']==$key){
                        $count++;
                    }
                }
                array_push($knn, ['scenario_id'=>$key, 'occurrences'=>$count]);
            }

            if(count($knn) > 1){
                for($i = 0; $i < count($knn)-1; $i++){
                    for($j = $i+1; $j < count($knn); $j++){
                        if($knn[$i]['occurrences'] < $knn[$j]['occurrences']){
                            $temp = $knn[$j];
                            $knn[$j] = $knn[$i];
                            $knn[$i] = $temp;
                        }
                    }
                }
            }
            if(count($knn) > 0){
                $model->scenario_id = $knn[0]['scenario_id'];
                $model->save();
            }


        }

        return $this->render('predictor', [
            'metrics' => Metric::findAll(['status'=>Metric::STATUS_ACTIVE]),
            'knn' => $knn,
            'k_delimiter' => $k,
            'model'=>$model
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

	public function actionChange_lang($lang,$url)
	{
		\Yii::$app->language = $lang;
		$cookie = new Cookie([
		    'name' => 'lang-farming',
            'value' => $lang,
            'expire' => time() + 60*60*24*30, // 30 days
        ]);
		\Yii::$app->getResponse()->getCookies()->add($cookie);

		return $this->redirect($url);
	}

    public function actionCkeditorupload()
    {
        $funcNum = $_REQUEST['CKEditorFuncNum'];

        if ($_FILES['upload']) {

            if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
                $message = Yii::t('backend', "Por favor, suba alguna imagen");
            } else if ($_FILES['upload']["size"] == 0 OR $_FILES['upload']["size"] > 5 * 1024 * 1024) {
                $message = Yii::t("backend","El tamaño de la imagen no debe exceder los ") . " 5MB";
            } else if (($_FILES['upload']["type"] != "image/jpg")
                AND ($_FILES['upload']["type"] != "image/jpeg")
                AND ($_FILES['upload']["type"] != "image/png")) {
                $message = Yii::t("backend","Ha ocurrido un error subiendo la imagen, por favor intente de nuevo");
            } else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {

                $message = Yii::t("backend","Formato de imagen no permitido, debe ser JPG, JPEG o PNG.");
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
                $message = Yii::t("backend","Imagen subida satisfactoriamente");
                //Giving permission to read and modify uploaded image
                chmod($realPath . $name, 0777);
            }

            echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'
                . $funcNum . '", "' . $url . '", "' . $message . '" );</script>';

        }
    }

    public function actionInfo_test()
    {
        return $this->render('phpinfo');
    }
}
