<?php

namespace backend\components;

use Yii;

class Footer_Bulk_Delete
{

    public static function getFooterBulkDelete($url)
    {

        $title_box_alert = 'Eliminar seleccionados';
        $message_confirm_multiple_delete = '¿Seguro desea eliminar los elementos seleccionados?';
        $message_confirm_one_delete = '¿Seguro desea eliminar el elemento seleccionado?';
        $message_no_selected = 'Por favor, seleccione los elementos a eliminar';

        $js = <<< JS
            $(document).ready(function(){
            $('#actionDeleteMultiple').click(function(){
                var Ids = $('#grid').yiiGridView('getSelectedRows');
                
                var count_selected = Ids.length;
                
                if(count_selected > 0)
                {
                      if(count_selected > 1)
                      {
                          krajeeDialog.confirm("$message_confirm_multiple_delete", function (result) {
                                if (result) { // ok button was pressed
                                    $.ajax({
                                    type: 'POST',
                                    url : "$url",
                                    data : {row_id: Ids},
                                    success : function() {
                                      $(this).closest('tr').remove(); //or whatever html you use for displaying rows
                                    }
                                });
                                } 
                            });
                      }
                      else 
                      {
                          krajeeDialog.confirm("$message_confirm_one_delete", function (result) {
                                if (result) { // ok button was pressed
                                    $.ajax({
                                    type: 'POST',
                                    url : "$url",
                                    data : {row_id: Ids},
                                    success : function() {
                                      $(this).closest('tr').remove(); //or whatever html you use for displaying rows
                                    }
                                });
                                } 
                            });
                      }
                }
                else
                {
                   BootstrapDialog.alert({title:'$title_box_alert', message:"$message_no_selected", type: 'type-danger'});
                }
            });
    });
JS;

        return $js;

    }


}