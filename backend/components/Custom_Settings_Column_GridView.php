<?php

namespace backend\components;

use Yii;
use yii\helpers\Html;

class Custom_Settings_Column_GridView
{

    public $serial_column = [];
    public $checkbox_column = [];
    public $action_column = [];
    public $togle_data_options = [];
    public $panel = [];
    public $toolbar = [];

    /**
     * Custom_Settings_Column_GridView constructor.

     */
    public function __construct($create_button, $dataProvider)
    {
        $serial_column = [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'header' => '#',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ];

        $checkbox_column = [
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ];

        $action_column = [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'dropdownOptions' => ['class' => 'float-right'],
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'template' => ['view', 'update', 'delete'],
            'viewOptions' => [
                'class' => 'btn btn-xs btn-default btn-flat',
                'title' => Yii::t('yii','View'),
                'data-toggle' => 'tooltip',
            ],
            'updateOptions' => [
                'class' => 'btn btn-xs btn-default btn-flat',
                'title' => Yii::t('yii','Update'),
                'data-toggle' => 'tooltip',
            ],
            'deleteOptions' => [
                'class' => 'btn btn-xs btn-danger btn-flat',
                'title' => Yii::t('yii','Delete'),
                'data-toggle' => 'tooltip',
                'data-confirm' =>  '¿Seguro desea eliminar este elemento?',
            ],
        ];

        $togle_data_options = [
            'minCount' => 20,
            'confirmMsg' => 'Existen {totalCount} registros. ¿Seguro desea mostrarlos todos?',['totalCount' => number_format($dataProvider->getTotalCount())],
            'all' => [
                'class' => 'btn btn-default btn-flat margin',
                'data-toggle' => 'tooltip',
            ],
            'page' => [
                'class' => 'btn btn-default btn-flat margin',
                'data-toggle' => 'tooltip',
            ],
        ];

        $panel = [
            'type' => 'default',
            'after'=>
                Html::a('<i class="fa fa-refresh"></i> Resetear', ['index'], ['class' => 'btn btn-default btn-flat margin','title'=> 'Resetear listado', 'data-toggle' => 'tooltip']).''.
                Html::button('<i class="fa fa-check-square-o"></i> <i class="fa fa-trash"></i> Eliminar' ,['id'=> 'actionDeleteMultiple','class'=>'btn btn-danger btn-flat margin','title'=> 'Eliminar seleccionados', 'data-toggle' => 'tooltip']),
            'before' => '',
        ];

        $toolbar = [
            [
                'content' =>
                    $create_button . ' '.
                    Html::a('<i class="fa fa-refresh"></i> Resetear', ['index'], ['class' => 'btn btn-default btn-flat margin','title'=> 'Resetear listado', 'data-toggle' => 'tooltip']),
            ],
            //'{export}',
            '{toggleData}',
        ];

        $this->togle_data_options = $togle_data_options;
        $this->serial_column = $serial_column;
        $this->checkbox_column = $checkbox_column;
        $this->action_column = $action_column;
        $this->panel = $panel;
        $this->toolbar = $toolbar;
    }

    /**
     * @param array $action_column
     */
    public function setActionColumn($action_column)
    {
        $this->action_column = $action_column;
    }

    /**
     * @return array
     */
    public function getSerialColumn()
    {
        return $this->serial_column;
    }

    /**
     * @return array
     */
    public function getCheckboxColumn()
    {
        return $this->checkbox_column;
    }

    /**
     * @return array
     */
    public function getActionColumn()
    {
        return $this->action_column;
    }

    /**
     * @return array
     */
    public function getTogleDataOptions()
    {
        return $this->togle_data_options;
    }

    /**
     * @return array
     */
    public function getPanel()
    {
        return $this->panel;
    }

    /**
     * @param array $panel
     */
    public function setPanel($panel)
    {
        $this->panel = $panel;
    }

    /**
     * @return array
     */
    public function getToolbar()
    {
        return $this->toolbar;
    }


}