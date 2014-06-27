<?php
/* @var $this ShortStopController */
/* @var $model ShortStop */

$this->breadcrumbs=array(
	'Short Stops'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ShortStop', 'url'=>array('index')),
	array('label'=>'Create ShortStop', 'url'=>array('create')),
	array('label'=>'View ShortStop', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ShortStop', 'url'=>array('admin')),
);
?>

<h1>Update ShortStop <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>