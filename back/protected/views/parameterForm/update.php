
<div class="headers">
	<div id="identity-update-button" onclick=""> </div>
	<h1>Update parameters for <?php echo Yii::app()->user->getState('current_company_name'); ?></h1>
</div>
<div id="all_content_container">
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<div id="screen_loading">
  <img id="spinner_image" src="<?php echo Yii::app()->request->baseUrl.'/public/images/spinner.gif';?>"/>
</div>
<div id="file_in_process_container" class="reset-form-container">
	The company data is being processed at the moment. You must wait until all the statistics are generated before viewing the fleet information or uploading an information file.
</div>
</div><!--all_content_container-->


<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/parameterForm/actions.js',CClientScript::POS_END);
?>

