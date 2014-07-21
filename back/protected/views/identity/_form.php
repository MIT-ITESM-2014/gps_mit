<?php
/* @var $this IdentityController */
/* @var $model Identity */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'identity-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name'); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<div class="row">
		<?php echo $form->checkBox($model,'is_admin',array('uncheckValue'=>'0', 'value'=>'1')); ?>
		<?php echo $form->labelEx($model,'is_admin'); ?>
	</div>

	<p class="note"> <span class="required">* Required fields</span></p>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'CREATE' : 'Save', array('id'=>'submit-button-create', 'class'=>'hide-button')); ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- form -->

<div class="clear"> </div>
