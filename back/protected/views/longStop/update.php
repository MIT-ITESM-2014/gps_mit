<?php
/* @var $this LongStopController */
/* @var $model LongStop */

$this->breadcrumbs=array(
	'Long Stops'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LongStop', 'url'=>array('index')),
	array('label'=>'Create LongStop', 'url'=>array('create')),
	array('label'=>'View LongStop', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LongStop', 'url'=>array('admin')),
);
?>

<h1>Update LongStop <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>