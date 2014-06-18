<?php
/* @var $this TruckController */
/* @var $model Truck */

$this->breadcrumbs=array(
	'Trucks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Truck', 'url'=>array('index')),
	array('label'=>'Manage Truck', 'url'=>array('admin')),
);
?>

<h1>Create Truck</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>