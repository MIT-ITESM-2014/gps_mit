<?php

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
	<h1>Manage Trucks for  <?php echo Yii::app()->user->getState('current_company_name');?></h1>
</div>

<div class="admin-list">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'truck-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'summaryText'=>' ',
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css', 'header' => ' '),
		'cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css',
		'htmlOptions' => array('class' => 'gridStyle'),
		'columns'=>array(
			//'id',
			'name',
			//'created_at',
			//'updated_at',
			array(
				'class'=>'CButtonColumn',
				'template' => '',
			),
		),
	)); ?>
</div>
