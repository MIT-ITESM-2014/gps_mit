<?php
/* @var $this LongStopController */
/* @var $model LongStop */

$this->breadcrumbs=array(
	'Long Stops'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List LongStop', 'url'=>array('index')),
	array('label'=>'Create LongStop', 'url'=>array('create')),
	array('label'=>'Update LongStop', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LongStop', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LongStop', 'url'=>array('admin')),
);
?>

<h1>View LongStop #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'route_id',
		'latitude',
		'longitude',
		'created_at',
		'updated_at',
	),
)); ?>
