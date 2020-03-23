<?php

//use mdm\admin\components\Helper;
use dmstr\widgets\Menu;
//use backend\models\settings\Setting;
use yii\helpers\Url;
use webvimark\modules\UserManagement\models\User;

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">

        </div>

        <?php

//        TODO: filtrar proyectos para el ususario autenticado
        $projects = [
            [
                'label' => "Proyecto X",
                'icon' => 'circle-o',
                'url' => "#",
            ],
        ];

        $menu_items = [

            //submenu de business
            [
                'label' => "Proyectos",
                'icon' => 'object-group',
                'url' => '#',
                'items' => $projects,
            ],

            //submenu de nomencladores
            [
                'label' => "Nomencladores",
                'icon' => 'list',
                'url' => '#',
                'items' => [
                    [
                        'label' => "Nomenclador 1",
                        'icon' => 'circle-o',
                        'url' => "#",
                    ],
                ],
            ],

            //submenu de administración
            [
                'label' => "Administración",
                'icon' => 'cogs',
                'url' => '#',
                'items' => [
                    [
                        'label' => "Usuarios",
                        'icon' => 'users',
                        'items' => \webvimark\modules\UserManagement\UserManagementModule::menuItems(),
                        'visible' => Yii::$app->user->isSuperadmin
                    ],
                    [
                        'label' => "Perfiles de Usuarios",
                        'icon' => 'users',
                        'url' => ['/user-profile/index'],
                        'visible' => User::hasRole("Admin")
                    ],
//
                    [
                        'label' => "Ajustes",
                        'icon' => 'cog',
                        'url' => ['/setting/update', 'id' => \backend\models\settings\Setting::SETTING_ID],
                    ],
//
//                    [
//                        'label' => 'Configuración de Correo',
//                        'icon' => 'circle-o',
//                        'url' => Url::toRoute(['/config-mailer/update', 'id'=>1]),
//                    ],
//
                    [
                        'label' => 'Configuraciones del sistema',
                        'icon' => 'circle-o',
                        'url' => Url::toRoute(['/system-config/index']),
                        'visible' => User::hasRole("Admin") && false
                    ],

                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'], 'visible' => YII_ENV_DEV && Yii::$app->user->isSuperadmin],

                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'], 'visible' => YII_ENV_DEV && Yii::$app->user->isSuperadmin],

                ],
            ],
        ];

        ?>

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => $menu_items,
                'encodeLabels' => false
            ]
        ) ?>

    </section>

</aside>
