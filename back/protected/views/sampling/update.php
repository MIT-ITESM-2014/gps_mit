<?php
/* @var $this SamplingController */
/* @var $model Sampling */

$this->breadcrumbs=array(
	'Samplings'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Sampling', 'url'=>array('index')),
	array('label'=>'Create Sampling', 'url'=>array('create')),
	array('label'=>'View Sampling', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sampling', 'url'=>array('admin')),
);
?>

<h1>Update Sampling <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>