If you press the "Reset" button all the information uploaded through csv files will be deleted. 

<form method="get" action="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/reset'?>">

  <input type="hidden" name="reset_confirmation" value="reset_confirmation">
   
  <?php echo CHtml::submitButton('Reset'); ?>

</form>
