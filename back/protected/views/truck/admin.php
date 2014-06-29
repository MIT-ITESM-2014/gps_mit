<?php
/* @var $this TruckController */
/* @var $model Truck */

$this->breadcrumbs=array(
	'Trucks'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Truck', 'url'=>array('index')),
	array('label'=>'Create Truck', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#truck-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="headers">
	<h1>Manage Trucks</h1>
</div>

<div class="admin-list">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'truck-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css'),
		'cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css',
		'summaryText'=>'Displaying {start} of {end} pages',
		'htmlOptions' => array('class' => 'gridStyle'),
		'columns'=>array(
			//'id',
			'identifier',
			//'created_at',
			//'updated_at',
			array(
				'class'=>'CButtonColumn',
			),
		),
	)); ?>
</div>