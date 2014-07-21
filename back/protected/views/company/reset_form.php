If you press the "Reset" button all the information uploaded through csv files will be deleted. 

<form method="post" action="<?php echo Yii::app()->getBaseUrl().'/index.php?r=company/reset'?>">

  <input type="hidden" name="reset_confirmation" value="reset_confirmation">
   
  <?php echo CHtml::submitButton('Reset'); ?>

</form>
