<?php
/* @var $this SamplingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Samplings',
);

$this->menu=array(
	array('label'=>'Create Sampling', 'url'=>array('create')),
	array('label'=>'Manage Sampling', 'url'=>array('admin')),
);
?>

<h1>Samplings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
