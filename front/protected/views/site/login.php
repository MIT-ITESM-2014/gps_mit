<?php
$this->pageTitle=Yii::app()->name . ' - Login';
?>
<div id="login-container">
  <div id="login-header" style="background-color: #1C1C1C; width:100%; height:59px;"></div>
  <div id="contents-field" style="width:100%;">
	  <div id="login-logo">
	    <?php echo "<img src='".Yii::app()->request->baseUrl."/public/images/CompassLoginLogo.png'>"; ?>
	  </div>
    <div id="login-form-container" style="margin-left:auto; margin-right:auto; width:500px; height:400px;">
      <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
	        'id'=>'login-form',
	        'enableClientValidation'=>true,
	        'clientOptions'=>array(
		        'validateOnSubmit'=>true,
	        ),
        )); ?>
	      <div class="row">
          <div class="login_field_container">
          <input id="LoginForm_username" class="login_field" type="text" name="LoginForm[username]" placeholder="User">
		      </div>
		      
	      </div>
	      <div class="row">
	        <div class="login_field_container">
		      <input id="LoginForm_password" class="login_field" type="password" name="LoginForm[password]" placeholder="Password">
		      </div>
		      
	      </div>
	      <div id="login_error_container">
	        <?php echo $form->error($model,'username'); ?>
		      <?php echo $form->error($model,'password'); ?>
	      </div>
	      <div id="login-submit-button-container" class="row buttons">
	        <div id="login_simulated_button">
	          Login
	        </div>
		      <?php echo CHtml::submitButton('Login'); ?>
	      </div>
      <?php $this->endWidget(); ?>
      </div><!-- form -->
    </div><!--Login form container-->
  </div><!--Contents Field-->
</div><!--Login Conatainer-->
<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/login/actions.js',CClientScript::POS_END);
?>
