<?php
/* @var $this IdentityCompanyController */
/* @var $model IdentityCompany */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'identity-company-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'identity_id'); ?>            
    <?php echo $form->dropDownList($model,'identity_id', $dropdown_data, array('empty'=>'select a user')); ?>
		<?php echo $form->error($model,'identity_id'); ?>
	</div>

	<p class="note"> <span class="required">* Required fields</span></p>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Save', array('class'=>'hide-button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
