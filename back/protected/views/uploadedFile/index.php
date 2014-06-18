<?php
/* @var $this UploadedFileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Uploaded Files',
);

$this->menu=array(
	array('label'=>'Create UploadedFile', 'url'=>array('create')),
	array('label'=>'Manage UploadedFile', 'url'=>array('admin')),
);
?>

<h1>Uploaded Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
