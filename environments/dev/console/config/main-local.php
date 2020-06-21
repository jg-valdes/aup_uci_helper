<?php

$config = [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
];

$config['components']['urlManager']['baseUrl'] = "http://aup-helper.uci.cu.local";
return $config;
