<?php
/* @var $this CompanyController */
/* @var $data Company */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('has_expected_routes')); ?>:</b>
	<?php echo CHtml::encode($data->has_expected_routes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('distance_ratio_long_stop')); ?>:</b>
	<?php echo CHtml::encode($data->distance_ratio_long_stop); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_ratio_long_stop')); ?>:</b>
	<?php echo CHtml::encode($data->time_ratio_long_stop); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>