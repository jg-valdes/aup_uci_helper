<?php

namespace backend\modules\v1\controllers;

use backend\models\business\Lead;
use backend\models\business\LandingPage;
use backend\models\business\Affiliate;
use backend\models\business\Offer;
use backend\models\business\UrlParams;
use backend\models\settings\Crm;
use common\models\ChangePassword;
use common\models\User;
use HttpInvalidParamException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


/**
 * Default controller for the `v1` module
 */
class LeadsController extends ApiController
{
    public $modelClass = 'backend\modules\v1\models\LeadModel';

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
            'list' => ['GET'],
            'order' => ['POST'],
            'update-status' => ['PUT'],
            'pay' => ['PUT'],
            'details' => ['GET'],
            'cancel' => ['DELETE']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['index'], $actions['view'], $actions['update']);

        return $actions;
    }

    /**
     * Renders the user profile view for the module
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionList()
    {
        $params = $this->getRequestParamsAsArray();
        $affiliateKey = ArrayHelper::getValue($params, "affiliate_key", false);
        $limit = ArrayHelper::getValue($params, "limit", 10);
        $offset = ArrayHelper::getValue($params, "offset", 0);

        try{
            $limit = intval($limit);
        }catch (\Exception $exception){
            $limit = 10;
        }

        try{
            $offset = intval($offset);
        }catch (\Exception $exception){
            $offset = 0;
        }

        $affiliate = $this->validateAffiliate($affiliateKey);
        $leads = Lead::getLeadsForAffiliate($affiliate->id, $limit, $offset);
        $message = Yii::t("backend", "Listado de Leads.");
        if ($affiliate->testAccess) {
            $message .= " " . Yii::t("backend", "Recuerde que está accediendo utilizando credenciales de prueba.");
        }
        $results = [];
        foreach ($leads as $request) {
            array_push($results, $request->getModelAsJson());
        }
        return [
            "message" => $message,
            "test_request" => $affiliate->testAccess,
            "limit" => $limit,
            "offset"=> $offset,
            "count"=> count($results),
            "results" => $results
        ];
    }

    /**
     * Allow to change own password for authenticated users
     * @return array
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionOrder()
    {
        $params = $this->getRequestParamsAsArray();
        $affiliateKey = ArrayHelper::getValue($params, "affiliate_key", false);
        $landingKey = ArrayHelper::getValue($params, "landing_key", false);
        $offerId = ArrayHelper::getValue($params, "goods_id", false);

        $affiliate = $this->validateAffiliate($affiliateKey);
        $landingPage = $this->validateLandingPage($landingKey);
        $offer = $this->validateOffer($offerId);

        $name = ArrayHelper::getValue($params, "name", null);
        $msisdn = ArrayHelper::getValue($params, "msisdn", null);
        $domain = ArrayHelper::getValue($params, "domain", null);
        $ip = ArrayHelper::getValue($params, "ip", null);
        $clientType = ArrayHelper::getValue($params, "client_type", null);
        $webmasterId = ArrayHelper::getValue($params, "webmaster_id", null);
        $age = ArrayHelper::getValue($params, "age", null);
        $growth = ArrayHelper::getValue($params, "growth", null);
        $weightLoss = ArrayHelper::getValue($params, "weight_loss", null);

        $model = new Lead(['status' => Lead::STATUS_ACTIVE, 'cocoleads_status' => Lead::STATUS_HOLD]);
        if($landingPage){
            $model->landing_page_id = $landingPage->id;
        }
        $model->offer_id = $offer->id;
        $model->affiliate_id = $affiliate->id;
        $model->is_test = Lead::IS_REAL;
        if($affiliate->testAccess || $offer->isTest()){
            $model->is_test = Lead::IS_TEST;
        }
        $model->name = $name;
        $model->msisdn = $msisdn;
        $model->domain = $domain;
        $model->ip = $ip;
        $model->client_type = $clientType;
        $model->webmaster_id = $webmasterId;
        $model->age = $age;
        $model->growth = $growth;
        $model->weight_loss = $weightLoss;
        $model->price = $offer->price;
        $model->payout = 0;

        Yii::info("*****Action Order Lead Detected*****", "CocoLeads");
        Yii::info("Keys : " . implode(array_keys($params), " -- "), "CocoLeads");
        Yii::info("Values: " . implode($params, " -- "), "CocoLeads");

        if ($model->save()) {

            $this->registerLeadParams($model->id, $params);
            $model = Lead::findOne($model->id);
            // return $model->syncToCrm(); uncomment for test
            return [
                "message" => Yii::t("backend", "Lead registrada satisfactoriamente."),
                "test_request" => $affiliate->testAccess,
                "result" => $model->getModelAsJson()
            ];
        } else {
            return [
                "statusCode" => "422",
                "success" => false,
                "errors" => $model->getErrors(),
                "test_request" => $affiliate->testAccess,
                "message" => Yii::t("backend", "Ha ocurrido un error registrando la Lead.")
            ];
        }
    }

    /**
     * Return Lead Details
     * @return array
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDetails()
    {
        $params = $this->getRequestParamsAsArray();
        $affiliateKey = ArrayHelper::getValue($params, "affiliate_key", false);
        $leadId = ArrayHelper::getValue($params, "lead_number", false);
        $affiliate = $this->validateAffiliate($affiliateKey);
        $lead = $this->validateLead($leadId);

        if ($lead->affiliate_id == $affiliate->id) {
            $message = Yii::t("backend", "Lead encontrada.");
            if ($affiliate->testAccess) {
                $message .= " " . Yii::t("backend", "Recuerde que está accediendo utilizando credenciales de prueba.");
            }
            return [
                "message" => $message,
                "test_request" => $affiliate->testAccess,
                "result" => $lead->getModelAsJson()
            ];
        } else {
            //403 Lead not authorized
            throw new ForbiddenHttpException(Yii::t("backend", "Su Empresa no está autorizada a acceder a este recurso."));
        }
    }

    /**
     * Return Lead Details
     * @return array
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCancel()
    {
        // Action for future
        throw new ForbiddenHttpException(Yii::t("backend", "Su Empresa no está autorizada a acceder a este recurso."));

        $params = $this->getRequestParamsAsArray();
        $affiliateKey = ArrayHelper::getValue($params, "affiliate_key", false);
        $leadId = ArrayHelper::getValue($params, "lead_number", false);
        $affiliate = $this->validateAffiliate($affiliateKey);
        $lead = $this->validateLead($leadId);

        if ($lead->affiliate_id == $affiliate->id) {
            $message = Yii::t("backend", "Lead encontrada.");
            if ($affiliate->testAccess) {
                $message .= " " . Yii::t("backend", "Recuerde que está accediendo utilizando credenciales de prueba.");
            }
            $lead->cocoleads_status = Lead::STATUS_TRASH;
            $lead->save(false);
            return [
                "message" => $message,
                "test_request" => $affiliate->testAccess,
                "result" => $lead->getModelAsJson()
            ];
        } else {
            //403 Lead not authorized
            throw new ForbiddenHttpException(Yii::t("backend", "Su Empresa no está autorizada a acceder a este recurso."));
        }
    }

    /**
     * Return Lead Details
     * @return array
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdateStatus()
    {
        $params = $this->getRequestParamsAsArray();
        $crmKey = ArrayHelper::getValue($params, "crm_key", false);
        $leadId = ArrayHelper::getValue($params, "lead_number", false);
        $cocoLeadStatus = ArrayHelper::getValue($params, "cocoleads_status", false);
        $crmStatus = ArrayHelper::getValue($params, "crm_status", false);
        $lead = $this->validateLead($leadId);
        $crm = $this->validateCRM($crmKey);

        if ($lead->offer->crm_id == $crm->id) {
            $message = Yii::t("backend", "Lead actualizada.");
            if($cocoLeadStatus){
                if(!ArrayHelper::keyExists($cocoLeadStatus, Lead::getLeadsStatus())){
                    $cocoLeadStatus = Lead::STATUS_HOLD;
                }
            }else{
                $cocoLeadStatus = Lead::STATUS_HOLD;
            }
            $lead->cocoleads_status = $cocoLeadStatus;
            $lead->crm_status = $crmStatus? $crmStatus : "";
            $lead->save();

            return [
                "message" => $message,
                "result" => $lead->getModelAsJson()
            ];
        } else {
            //403 Lead not authorized
            throw new ForbiddenHttpException(Yii::t("backend", "Su CRM no está autorizado a acceder a este recurso."));
        }

    }

    /**
     * Return Lead Details
     * @return array
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionPay()
    {
        $params = $this->getRequestParamsAsArray();
        $affiliateKey = ArrayHelper::getValue($params, "affiliate_key", false);
        $leadId = ArrayHelper::getValue($params, "lead_number", false);
        $amountParam = ArrayHelper::getValue($params, "amount", false);
        $affiliate = $this->validateAffiliate($affiliateKey);
        $lead = $this->validateLead($leadId);
        $amount = $this->validateAmount($amountParam);

        if ($lead->affiliate_id == $affiliate->id) {
            $message = Yii::t("backend", "Lead pagada correctamente.");
            if ($affiliate->testAccess) {
                $message .= " " . Yii::t("backend", "Recuerde que está accediendo utilizando credenciales de prueba.");
            }

            $lead->paid = $amount;
            $lead->save();

            return [
                "message" => $message,
                "test_request" => $affiliate->testAccess,
                "result" => $lead->getModelAsJson()
            ];
        } else {
            //403 Lead not authorized
            throw new ForbiddenHttpException(Yii::t("backend", "Su Empresa no está autorizada a acceder a este recurso."));
        }
    }

    /**:::::::::::::Validators Section:::::::::::::::**/

    /**
     * Return Lead Object or throw exceptions
     * @param integer $leadId
     * @return Lead|null
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    private function validateLead($leadId)
    {
        if (!$leadId) {
            //422 missing lead_number param
            throw new BadRequestHttpException(Yii::t("backend", "Faltan parámetros en la consulta, asegúrese de incluir el número de Lead 'lead_number'."));
        }

        if (!is_numeric($leadId)) {
            //403 Attack XSS
            throw new ForbiddenHttpException(Yii::t("backend", "El identificador de la Lead no es numérico, por favor evite ser bloqueado en nuestro sistema."));
        }

        $lead = Lead::findOne($leadId);
        if (isset($lead)) {
            return $lead;
        } else {
            //404 Lead not found
            throw new NotFoundHttpException(Yii::t("backend", "No se ha encontrado ninguna Lead para el número: ") . $leadId);
        }
    }

    /**
     * Return Affiliate Object or throw exceptions
     * @param string $affiliateKey
     * @return Affiliate|null
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    private function validateAffiliate($affiliateKey)
    {
        if (!$affiliateKey) {
            //422 missing lead_number param
            throw new BadRequestHttpException(Yii::t("backend", "Faltan parámetros en la consulta, asegúrese de incluir el token de acceso 'affiliate_key'."));
        }

        $affiliate = Affiliate::findAffiliateByAccessToken($affiliateKey);
        if (isset($affiliate)) {
            return $affiliate;
        } else {
            //404 Lead not found
            throw new NotFoundHttpException(Yii::t("backend", "No se ha encontrado ningún Afiliado con las credenciales enviadas."));
        }
    }

    /**
     * Return Lead Object or throw exceptions
     * @param integer $offerId
     * @return Offer|null
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    private function validateOffer($offerId)
    {
        if (!$offerId) {
            //422 missing lead_number param
            throw new BadRequestHttpException(Yii::t("backend", "Faltan parámetros en la consulta, asegúrese de incluir el número de la Oferta 'goods_id'."));
        }

        if (!is_numeric($offerId)) {
            //403 Attack XSS
            throw new ForbiddenHttpException(Yii::t("backend", "El identificador de la Oferta no es numérico, por favor evite ser bloqueado en nuestro sistema."));
        }

        $offer = Offer::findOne(['id'=>$offerId, 'status'=>Offer::STATUS_ACTIVE]);
        if (isset($offer)) {
            return $offer;
        } else {
            //404 Lead not found
            throw new NotFoundHttpException(Yii::t("backend", "No se ha encontrado ninguna Oferta para el número: ") . $offerId);
        }
    }

    /**
     * Return Affiliate Object or throw exceptions
     * @param string $landingKey
     * @return LandingPage|null
     * @throws NotFoundHttpException
     */
    private function validateLandingPage($landingKey)
    {
        if (!$landingKey) {
            return false;
        }

        $landingPage = LandingPage::findLandingPageByAccessToken($landingKey);
        if (isset($landingPage)) {
            return $landingPage;
        } else {
            //404 Lead not found
            throw new NotFoundHttpException(Yii::t("backend", "No se ha encontrado ninguna Landing Page con las credenciales enviadas."));
        }
    }

    /**
     * Return CRM Object or throw exceptions
     * @param string $crmKey
     * @return Crm|null
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    private function validateCRM($crmKey)
    {
        if (!$crmKey) {
            //422 missing lead_number param
            throw new BadRequestHttpException(Yii::t("backend", "Faltan parámetros en la consulta, asegúrese de incluir el token del CRM 'crm_key'."));
        }

        $crm = Crm::findCRMByAccessToken($crmKey);
        if (isset($crm)) {
            return $crm;
        } else {
            //404 Lead not found
            throw new NotFoundHttpException(Yii::t("backend", "No se ha encontrado ningún CRM con las credenciales enviadas."));
        }
    }

    /**
     * Return double amount or throw exceptions
     * @param double $amount
     * @return double|null
     * @throws BadRequestHttpException
     */
    private function validateAmount($amount)
    {
        if (!$amount) {
            //422 missing lead_number param
            throw new BadRequestHttpException(Yii::t("backend", "Faltan parámetros en la consulta, asegúrese de incluir el monto 'amount'."));
        }

        if (!is_numeric($amount)) {
            //422 value no valid
            throw new BadRequestHttpException(Yii::t("backend", "El monto definido no es un valor numérico."));
        }
        return doubleval($amount);
    }

    /**
     * @param $leadId integer Lead ID
     * @param $params array url params
     */
    private function registerLeadParams($leadId, $params)
    {
        $urlParamsSub1 = ArrayHelper::getValue($params, "url_params_sub1", null);
        if($urlParamsSub1 == null){
            $urlParamsSub1 = ArrayHelper::getValue($params, "url_params[sub1]", null);
        }
        $urlParamsSub2 = ArrayHelper::getValue($params, "url_params_sub2", null);
        if($urlParamsSub2 == null){
            $urlParamsSub2 = ArrayHelper::getValue($params, "url_params[sub2]", null);
        }
        $urlParamsSub3 = ArrayHelper::getValue($params, "url_params_sub3", null);
        if($urlParamsSub3 == null){
            $urlParamsSub3 = ArrayHelper::getValue($params, "url_params[sub3]", null);
        }
        $urlParamsSub4 = ArrayHelper::getValue($params, "url_params_sub4", null);
        if($urlParamsSub4 == null){
            $urlParamsSub4 = ArrayHelper::getValue($params, "url_params[sub4]", null);
        }
        $urlParamsSub5 = ArrayHelper::getValue($params, "url_params_sub5", null);
        if($urlParamsSub5 == null){
            $urlParamsSub5 = ArrayHelper::getValue($params, "url_params[sub5]", null);
        }
        $urlParamsUtmSource = ArrayHelper::getValue($params, "url_params_utm_source", null);
        if($urlParamsUtmSource == null){
            $urlParamsUtmSource = ArrayHelper::getValue($params, "url_params[utm_source]", null);
        }
        $urlParamsUtmContent = ArrayHelper::getValue($params, "url_params_utm_content", null);
        if($urlParamsUtmContent == null){
            $urlParamsUtmContent = ArrayHelper::getValue($params, "url_params[utm_content]", null);
        }
        $urlParamsUtmCampaign = ArrayHelper::getValue($params, "url_params_utm_campaign", null);
        if($urlParamsUtmCampaign == null){
            $urlParamsUtmCampaign = ArrayHelper::getValue($params, "url_params[utm_campaign]", null);
        }
        $urlParamsUtmTerm = ArrayHelper::getValue($params, "url_params_utm_term", null);
        if($urlParamsUtmTerm == null){
            $urlParamsUtmTerm = ArrayHelper::getValue($params, "url_params[utm_term]", null);
        }

        $urlParams = new UrlParams([
            'lead_id' => $leadId,
            'status' => UrlParams::STATUS_ACTIVE,
            'sub1' => $urlParamsSub1,
            'sub2' => $urlParamsSub2,
            'sub3' => $urlParamsSub3,
            'sub4' => $urlParamsSub4,
            'sub5' => $urlParamsSub5,
            'utm_source' => $urlParamsUtmSource,
            'utm_content' => $urlParamsUtmContent,
            'utm_campaign' => $urlParamsUtmCampaign,
            'utm_term' => $urlParamsUtmTerm,
        ]);
        if(!$urlParams->save()){
            Yii::info("URL Params can not be saved", "CocoLeads");
            Yii::info(implode(" -- ", $urlParams->getFirstErrors()), "CocoLeads");
        }
    }
}
