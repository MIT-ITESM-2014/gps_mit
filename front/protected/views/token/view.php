<?php
/* @var $this TokenController */
/* @var $model Token */

$this->breadcrumbs=array(
	'Tokens'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Token', 'url'=>array('index')),
	array('label'=>'Create Token', 'url'=>array('create')),
	array('label'=>'Update Token', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Token', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Token', 'url'=>array('admin')),
);
?>

<h1>View Token #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'identity',
		'token',
		'secret',
		'expires_at',
		'user_id',
		'created_at',
		'updated_at',
	),
)); ?>