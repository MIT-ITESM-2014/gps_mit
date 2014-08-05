
<?php

$options = "";
foreach ($trucks as $t)
{
  $options = $options . " newOption = $('<option value=\"".$t->id."\">".$t->name."</option>');  $('#truck_selector').append(newOption);";
}

?>


<?php 

$this->renderPartial('_map', array(
  'script'=>$script, 
  'trucks'=>$trucks, 
  'model'=>$model,
 )); 
?>


<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/routes/actions.js',CClientScript::POS_END);
?>
<?php
Yii::app()->clientScript->registerScript('addOptions', "
  var newOption;
  ".$options."
  $('#truck_selector').append(newOption);
  var opt = $('#truck_selector option:eq(1)').val();
  $('#truck_selector').val(opt); 
  updateAvailableDate();
",CClientScript::POS_END);
?>
