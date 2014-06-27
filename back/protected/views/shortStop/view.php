<?php
/* @var $this ShortStopController */
/* @var $model ShortStop */

$this->breadcrumbs=array(
	'Short Stops'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ShortStop', 'url'=>array('index')),
	array('label'=>'Create ShortStop', 'url'=>array('create')),
	array('label'=>'Update ShortStop', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ShortStop', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ShortStop', 'url'=>array('admin')),
);
?>

<h1>View ShortStop #<?php echo $model->id; ?></h1>

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
