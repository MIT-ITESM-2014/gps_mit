<div class="headers">
	<h1>View Truck #<?php echo $model->id; ?></h1>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'created_at',
		'updated_at',
	),
)); ?>
