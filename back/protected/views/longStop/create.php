<?php
/* @var $this LongStopController */
/* @var $model LongStop */

$this->breadcrumbs=array(
	'Long Stops'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LongStop', 'url'=>array('index')),
	array('label'=>'Manage LongStop', 'url'=>array('admin')),
);
?>

<h1>Create LongStop</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>