<?php
/* @var $this SamplingController */
/* @var $model Sampling */

$this->breadcrumbs=array(
	'Samplings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Sampling', 'url'=>array('index')),
	array('label'=>'Manage Sampling', 'url'=>array('admin')),
);
?>

<h1>Create Sampling</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>