<?php 
  $this->renderPartial('_map', array(
    'model'=>$model,
  )); 
?>

<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/routes/actions_map.js',CClientScript::POS_HEAD);
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/routes/actions.js',CClientScript::POS_END);
?>
