<?php
/* @var $this ShortStopController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Short Stops',
);

$this->menu=array(
	array('label'=>'Create ShortStop', 'url'=>array('create')),
	array('label'=>'Manage ShortStop', 'url'=>array('admin')),
);
?>

<h1>Short Stops</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
