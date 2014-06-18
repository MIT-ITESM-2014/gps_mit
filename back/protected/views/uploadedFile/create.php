<?php
/* @var $this UploadedFileController */
/* @var $model UploadedFile */

$this->breadcrumbs=array(
	'Uploaded Files'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UploadedFile', 'url'=>array('index')),
	array('label'=>'Manage UploadedFile', 'url'=>array('admin')),
);
?>

<h1>Create UploadedFile</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>