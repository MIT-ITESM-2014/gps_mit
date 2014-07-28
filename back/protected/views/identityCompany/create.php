<?php

Yii::app()->clientScript->registerScript('submitForm', "
$('#add-user-button').click( function(){
  $('#identity-company-form input').click();
});");
?>

<div class="headers">
	<div id="add-user-button" onclick=""> </div>
	<h1>Add User to Fleet</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model, 'dropdown_data'=>$dropdown_data)); ?>
