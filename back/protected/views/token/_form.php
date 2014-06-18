<?php
/* @var $this TokenController */
/* @var $model Token */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'token-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'identity_id'); ?>
		<?php echo $form->textField($model,'identity_id'); ?>
		<?php echo $form->error($model,'identity_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'token'); ?>
		<?php echo $form->textArea($model,'token',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'token'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'secret'); ?>
		<?php echo $form->textArea($model,'secret',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'secret'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'expires_at'); ?>
		<?php echo $form->textField($model,'expires_at'); ?>
		<?php echo $form->error($model,'expires_at'); ?>
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

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->