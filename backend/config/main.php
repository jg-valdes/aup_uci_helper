<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'aup_uci_helper-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'headers'],
    'name'=> 'AUP vUCI',
    'language' => 'es',
    'sourceLanguage' => 'es',
    'modules' => [
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',

            // 'enableRegistration' => true,

            // Add regexp validation to passwords. Default pattern does not restrict user and can enter any set of characters.
            // The example below allows user to enter :
            // any set of characters
            // (?=\S{8,}): of at least length 8
            // (?=\S*[a-z]): containing at least one lowercase letter
            // (?=\S*[A-Z]): and at least one uppercase letter
            // (?=\S*[\d]): and at least one number
            // $: anchored to the end of the string

            //'passwordRegexp' => '^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$',


            // Here you can set your handler to change layout for any controller or action
            // Tip: you can use this event in any module
            'on beforeAction' => function (yii\base\ActionEvent $event) {
                if ($event->action->uniqueId == 'user-management/auth/login') {
                    $event->action->controller->layout = 'loginLayout.php';
                };
            },
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
//            'baseUrl' => '',
        ],
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            'enableAutoLogin' => false,
            'loginUrl' => ['/user-profile/login'],
            'identityCookie' => [
                'name' => '_backendUser', // unique for backend
                'path' => '/backend/web',  // correct path for the backend app.
            ],

            // Comment this if you don't want to record user logins
            'on afterLogin' => function ($event) {
                \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
            }
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'aup_uci_helper_session-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mail' => [
            'class' => 'backend\mail\CustomMailer',
            'viewPath' => '@common/mail',
            'enableSwiftMailerLogging' => true,
            'useFileTransport' => false,
        ],
    ],
    'params' => $params,
];
