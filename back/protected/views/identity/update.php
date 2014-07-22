<?php

Yii::app()->clientScript->registerScript('submitForm', "
$('#identity-update-button').click( function(){
  $('#submit-button-create').click();
});");

?>


<div class="headers">
	<div id="identity-update-button" onclick=""> </div>
	<h1>Update User <?php echo $model->name." ".$model->last_name; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
