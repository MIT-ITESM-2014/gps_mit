<?php
/* @var $this TruckController */
/* @var $model Truck */

$this->breadcrumbs=array(
	'Trucks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Truck', 'url'=>array('index')),
	array('label'=>'Create Truck', 'url'=>array('create')),
	array('label'=>'View Truck', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Truck', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Update Truck <?php echo $model->id; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>