<?php
/* @var $this CompanyController */
/* @var $model Company */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'company-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'has_expected_routes'); ?>
		<?php echo $form->textField($model,'has_expected_routes'); ?>
		<?php echo $form->error($model,'has_expected_routes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'distance_ratio_long_stop'); ?>
		<?php echo $form->textField($model,'distance_ratio_long_stop'); ?>
		<?php echo $form->error($model,'distance_ratio_long_stop'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_ratio_long_stop'); ?>
		<?php echo $form->textField($model,'time_ratio_long_stop'); ?>
		<?php echo $form->error($model,'time_ratio_long_stop'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->