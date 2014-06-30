<?php
/* @var $this IdentityController */
/* @var $model Identity */

$this->breadcrumbs=array(
	'Identities'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Identity', 'url'=>array('index')),
	array('label'=>'Create Identity', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#identity-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="headers">
	<h1>Manage Identities</h1>
</div>

<div class="admin-list">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'identity-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css'),
		'cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css',
		'summaryText'=>'Displaying {start} of {end} pages',
		'htmlOptions' => array('class' => 'gridStyle'),
		'columns'=>array(
			//'id',
			'name',
			'last_name',
			'username',
			'mail',
			//'password',
			/*
			'created_at',
			'updated_at',
			*/
			array(
				'class'=>'CButtonColumn',
			),
		),
	)); ?>
</div>