<?php

Yii::app()->clientScript->registerScript('submitForm', "
$('#button-box').click( function(){
  $('#submit-button-create').click();
});");
?>

<div class="headers">
	<div id="button-box" onclick=""> </div>
	<h1>Create User</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
