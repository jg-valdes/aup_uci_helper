<?php

namespace backend\modules\v1\models;

use Yii;
use yii\helpers\Url;
use backend\models\business\Lead;

class LeadModel extends Lead
{
    public function fields()
    {
        return [
            'lead_number' => function(Lead $model){
                return $model->id;
            },
            'is_test' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'goods_id' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'geo' => function(Lead $model){
                return $model->getCountryAsJson();
            },
            'product' => function(Lead $model){
                return $model->getProductAsJson();
            },

            'affiliate' => function(Lead $model){
                return $model->getAffiliateAsJson();
            },
            'webmaster_id' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'status' => function(Lead $model){
                return $model->getStatusValue(true);
            },
            'status_code' => function(Lead $model){
                return $model->crm_status;
            },
            'price' => function(Lead $model){
                return $model->getPrice();
            },
            'payout' => function(Lead $model){
                return $model->getPayout();
            },
            'client_type' => function(Lead $model){
                return $model->getClientType(true);
            },
            'name' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'msisdn' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'domain' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'ip' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'age' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'growth' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'weight_loss' => function(Lead $model){
                return $model->getIsTest(true);
            },
            'url_params' => function(Lead $model){
                return ($model->urlParams)? $model->urlParams->getModelAsJson() : [];
            },
        ];
    }
}