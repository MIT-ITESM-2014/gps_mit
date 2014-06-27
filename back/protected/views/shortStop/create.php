<?php
/* @var $this ShortStopController */
/* @var $model ShortStop */

$this->breadcrumbs=array(
	'Short Stops'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ShortStop', 'url'=>array('index')),
	array('label'=>'Manage ShortStop', 'url'=>array('admin')),
);
?>

<h1>Create ShortStop</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>