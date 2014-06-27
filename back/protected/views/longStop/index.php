<?php
/* @var $this LongStopController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Long Stops',
);

$this->menu=array(
	array('label'=>'Create LongStop', 'url'=>array('create')),
	array('label'=>'Manage LongStop', 'url'=>array('admin')),
);
?>

<h1>Long Stops</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
