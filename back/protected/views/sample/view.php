<?php
/* @var $this SampleController */
/* @var $model Sample */

$this->breadcrumbs=array(
	'Samples'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Sample', 'url'=>array('index')),
	array('label'=>'Create Sample', 'url'=>array('create')),
	array('label'=>'Update Sample', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Sample', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Sample', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>View Sample #<?php echo $model->id; ?></h1>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'truck_id',
		'latitude',
		'longitude',
		'speed',
		'datetime',
		'created_at',
		'updated_at',
	),
)); ?>
