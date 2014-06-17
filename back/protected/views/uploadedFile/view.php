<?php
/* @var $this UploadedFileController */
/* @var $model UploadedFile */

$this->breadcrumbs=array(
	'Uploaded Files'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UploadedFile', 'url'=>array('index')),
	array('label'=>'Create UploadedFile', 'url'=>array('create')),
	array('label'=>'Update UploadedFile', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UploadedFile', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UploadedFile', 'url'=>array('admin')),
);
?>

<h1>View UploadedFile #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'filename',
		'identity_id',
		'created_at',
		'updated_at',
	),
)); ?>
