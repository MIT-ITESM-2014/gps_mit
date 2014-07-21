<div class="headers">
	<h1>Manage Samples</h1>
</div>

<div class="admin-list">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'sample-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'summaryText'=>' ',
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css', 'header' => ''),
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
