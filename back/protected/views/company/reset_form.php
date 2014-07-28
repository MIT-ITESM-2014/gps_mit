<div class="headers">
	<h1>Reset <?php echo Yii::app()->user->getState('current_company_name');?> Fleet Data  </h1>
</div>	

<div class="clear"></div>

<div class="reset-form-container">
If you press the "Reset" button all the information uploaded through csv files will be deleted. 

<form method="post" action="<?php echo Yii::app()->getBaseUrl().'/index.php?r=company/reset'?>">

  <input type="hidden" name="reset_confirmation" value="reset_confirmation">
   
  <?php echo CHtml::submitButton('Reset', array('id'=>'resetButton')); ?>

  <div id="fleet-reset-button" class="fleet-reset-button"> </div>

</form>

<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/company/reset/actions.js',CClientScript::POS_END);
?>
</div>