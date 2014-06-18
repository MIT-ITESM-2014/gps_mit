<?php
/* @var $this TruckController */
/* @var $model Truck */

$this->breadcrumbs=array(
	'Trucks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Truck', 'url'=>array('index')),
	array('label'=>'Create Truck', 'url'=>array('create')),
	array('label'=>'Update Truck', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Truck', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Truck', 'url'=>array('admin')),
);
?>

<h1>View Truck #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'identifier',
		'created_at',
		'updated_at',
	),
)); ?>
