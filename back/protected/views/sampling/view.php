<?php
/* @var $this SamplingController */
/* @var $model Sampling */

$this->breadcrumbs=array(
	'Samplings'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Sampling', 'url'=>array('index')),
	array('label'=>'Create Sampling', 'url'=>array('create')),
	array('label'=>'Update Sampling', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Sampling', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Sampling', 'url'=>array('admin')),
);
?>

<h1>View Sampling #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'truck_id',
	),
)); ?>
