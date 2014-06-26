<?php
/* @var $this IdentityController */
/* @var $model Identity */

$this->breadcrumbs=array(
	'Identities'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Identity', 'url'=>array('index')),
	array('label'=>'Create Identity', 'url'=>array('create')),
	array('label'=>'Update Identity', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Identity', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Identity', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>View Identity #<?php echo $model->id; ?></h1>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'last_name',
		'username',
		'mail',
		'password',
		'created_at',
		'updated_at',
	),
)); ?>
