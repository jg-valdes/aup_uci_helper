<?php

use mdm\admin\components\Helper;
use dmstr\widgets\Menu;
use backend\models\settings\Setting;
use common\models\User;

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">

        </div>

        <?php

        $menu_items = [
            //Inicio
            [
                'label' => Yii::t("backend", "Panel de Control"),
                'icon' => 'dashboard',
                'url' => '/',
            ],


            //Administracion
            [
                'label' => Yii::t("backend", "Administración"),
                'icon' => 'cogs',
                'url' => '#',
                'items' => [
                    [
                        'label' => Yii::t("backend", "Escenarios"),
                        'icon' => 'tags',
                        'url' => ['/scenario/index'],
                    ],
                    [
                        'label' => Yii::t("backend", "Métricas"),
                        'icon' => 'arrows',
                        'url' => ['/metric/index'],
                    ],
                    [
                        'label' => Yii::t("backend", "Procesos"),
                        'icon' => 'archive',
                        'url' => ['/process/index'],
                    ],
                    [
                        'label' => Yii::t("backend", "Artefactos"),
                        'icon' => 'file-word-o',
                        'url' => ['/artifact/index'],
                    ],
                    [
                        'label' => Yii::t("backend", "Roles AUP"),
                        'icon' => 'users',
                        'url' => ['/aup-role/index'],
                    ],
                    [
                        'label' => Yii::t("backend", "Disciplinas"),
                        'icon' => 'circle-o',
                        'url' => ['/discipline/index'],
                    ],

                ],
            ],

            //Seguridad
            [
                'label' => Yii::t("backend", "Seguridad"),
                'icon' => 'shield',
                'url' => '#',
                'items' => [
                    [
                        'label' => Yii::t("backend", "Usuarios"),
                        'icon' => 'circle',
                        'url' => ['/security/user'],
                    ],
                    [
                        'label' => Yii::t("backend", "Rutas"),
                        'icon' => 'circle',
                        'url' => ['/security/route'],
                        'visible' => false
                    ],

                    [
                        'label' => Yii::t("backend", "Permisos"),
                        'icon' => 'circle',
                        'url' => ['/security/permission'],
                    ],
                    [
                        'label' => Yii::t("backend", "Roles"),
                        'icon' => 'circle',
                        'url' => ['/security/role'],
                    ],
                    [
                        'label' => Yii::t("backend", "Sistema"),
                        'icon' => 'cog',
                        'url' => ['/setting/update', 'id' => Setting::getIdSettingByActiveLanguage()],
                    ],
                ],
            ],

            //Support
            [
                'label' => Yii::t("backend", "Soporte"),
                'icon' => 'cog',
                'url' => '#',
                'items' => [
                    [
                        'label' => Yii::t('backend', 'Base de Casos'),
                        'icon' => 'database',
                        'url' => ['/ia-case/index'],
                    ],
                    [
                        'label' => Yii::t('backend', 'Grupos de FAQ'),
                        'icon' => 'list',
                        'url' => ['/faq-group/index'],
                    ],

                    [
                        'label' => Yii::t('backend', 'FAQ'),
                        'icon' => 'question',
                        'url' => ['/faq/index'],
                    ],

                ],
            ],

            //Desarrolladores
            [
                'label' => Yii::t("backend", "DESARROLLADORES"),
                'icon' => 'warning',
                'url' => '#',
                'items' => [
                    [
                        'label' => Yii::t('backend', 'Envío de correo'),
                        'icon' => 'envelope',
                        'url' => ['/config-mailer/update', 'id' => 1],
                    ],

                    [
                        'label' => Yii::t("backend", "Idiomas"),
                        'icon' => 'flag',
                        'url' => ['/language/index'],
                    ],

                    [
                        'label' => Yii::t("backend", "Traducciones"),
                        'icon' => 'language',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t("backend", "Listado"), 'icon' => 'list', 'url' => ['/source-message/index'],],
                            ['label' => Yii::t("backend", "Importar"), 'icon' => 'upload', 'url' => ['/source-message/import'],],

                        ],
                    ],

                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii/default'], 'visible' => Yii::$app->user->can(User::ROLE_SUPERADMIN) && YII_ENV_DEV],

                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'], 'visible' => Yii::$app->user->can(User::ROLE_SUPERADMIN) && YII_ENV_DEV],


                ],
            ],
        ];

        $menu_items = Helper::filter($menu_items);

        ?>

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => $menu_items
            ]
        ) ?>

    </section>

</aside>
