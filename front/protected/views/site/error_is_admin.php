
<div id="error-admin-container">
	We are sorry <?php echo Yii::app()->user->getState('first_name'); ?>. Administrators cannot use this section. 
</div>


<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/error_admin/actions.js',CClientScript::POS_END);
?>