

<?php 

$this->renderPartial('_map', array(
  'script'=>$script, 
  'trucks'=>$trucks, 
  'model'=>$model,
 )); 
?>


<?php
Yii::app()->clientScript->registerScript('addOptions', "

",CClientScript::POS_END);
?>
