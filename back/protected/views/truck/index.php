<?php
/* @var $this TruckController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Trucks',
);

$this->menu=array(
	array('label'=>'Create Truck', 'url'=>array('create')),
	array('label'=>'Manage Truck', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Trucks</h1>
</div>

<div id="list-contents">
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
</div>
