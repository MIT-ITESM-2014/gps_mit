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
		  array(
	      /*'name'=>'User',*/
	      /*'header'=>'User',
	      'value'=>'$data->identity->name." ".$data->identity->last_name',*/
	      /*'name'=>'identity.fullname',*/
	      'header'=>'Truck',
	      'name'=>'truck_name_search',
	      'value'=>'$data->truck->name',
	    ),
			//'id',
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
				'template' =>'{delete}',
			),
		),
	)); ?>
</div>
