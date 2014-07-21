<?php

Yii::app()->clientScript->registerScript('submitForm', "
$('#identity-update-button').click( function(){
  $('#company-form input').click();
});");
?>

<div class="headers">
	<div id="identity-update-button" onclick=""> </div>
	<h1>Update Fleet <?php echo $model->name; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
