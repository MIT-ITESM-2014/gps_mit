<?php
/* @var $this SampleController */
/* @var $model Sample */

$this->breadcrumbs=array(
	'Samples'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Sample', 'url'=>array('index')),
	array('label'=>'Create Sample', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sample-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="headers">
	<h1>Manage Samples</h1>
</div>

<div class="admin-list">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'sample-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css'),
		'cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css',
		'summaryText'=>'Displaying {start} of {end} pages',
		'htmlOptions' => array('class' => 'gridStyle'),
		'columns'=>array(
			//'id',
			'truck_id',
			'latitude',
			'longitude',
			//'speed',
			'datetime',
			/*
			'created_at',
			'updated_at',
			*/
			array(
				'class'=>'CButtonColumn',
				'template' =>'',
			),
		),
	)); ?>
</div>
