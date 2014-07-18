<div class="headers">
	<a href="<?php echo Yii::app()->createUrl('identity/create', array())?>"><div id="button-box-admin"> </div></a>
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
			//'password',
			/*
			'created_at',
			'updated_at',
			*/
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}',
				'updateButtonImageUrl' => "",//Yii::app()->baseUrl . '/public/images/icons/' . 'Edit.png',
				'updateButtonOptions' => array('class' => 'update-button'),
				'deleteButtonImageUrl' => Yii::app()->baseUrl . '/public/images/icons/' . 'DeleteColor.png',
				'deleteButtonOptions' => array('class' => 'delete-button')
			),
		),
	)); ?>
</div>
