<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name'=>'AUP vUCI',
    'language' => 'es',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'defaultTimeZone' => 'America/Havana',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:Y-m-d h:i A',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => '$',
        ],
        // Headers security
        'headers' => [
            'class' => 'common\components\HeaderSecurity',
            'xFrameOptions' => 'SAMEORIGIN',
            'xPoweredBy' => 'Centro de Tecnologías Interactivas, UCI, Cuba',
        ]
    ],
];
