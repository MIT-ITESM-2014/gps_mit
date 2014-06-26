
<?php

$options = "";
foreach ($trucks as $t)
{
  $options = $options . " newOption = $('<option value=\"".$t->id."\">".$t->identifier."</option>');  $('#truck_selector').append(newOption);";
}

?>

<div id="map-canvas" >
</div>

<div id="map-legend">
<?php
//echo $form->textField($model,'user_end_date'); 
  $this->widget('zii.widgets.jui.CJuiDatePicker', array(
          'model'=>$model,
          'attribute'=>'start_date',
          'options'   => array(
                  'changeYear' => true,
                  'dateFormat' => 'mm/dd/yy',
                  //'timeFormat' => '',//'hh:mm tt' default
                  'beforeShowDay'=>'js:editDays',
                  'minDate'=>$min_date,
                  'maxDate'=>$max_date,
          ),
  ));
                
Yii::app()->clientScript->registerScript('editDays', "
  function editDays(date) {
    var disabledDates = [".$inactive_days_string."];
    for (var i = 0; i < disabledDates.length; i++) {
      if (new Date(disabledDates[i]).toString() == date.toString()) {
        return [false,''];
      }
    }
    return [true,''];
  }
");
?>

<select id="truck_selector" name="truck_selector">
</select>

<div id="button_update_map" name="button_update_map">
Actualizar
</div>

</div>
<?php Yii::app()->clientScript->registerScript('start_map.js',$script, CClientScript::POS_HEAD); ?>


<?php
Yii::app()->clientScript->registerScript('addOptions', "
  var newOption;
  ".$options."
  $('#truck_selector').append(newOption);  
");
?>
