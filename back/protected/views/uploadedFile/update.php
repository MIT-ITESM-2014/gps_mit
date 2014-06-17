<?php
/* @var $this UploadedFileController */
/* @var $model UploadedFile */

$this->breadcrumbs=array(
	'Uploaded Files'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UploadedFile', 'url'=>array('index')),
	array('label'=>'Create UploadedFile', 'url'=>array('create')),
	array('label'=>'View UploadedFile', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UploadedFile', 'url'=>array('admin')),
);
?>

<h1>Update UploadedFile <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>