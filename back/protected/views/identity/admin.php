<div class="headers">
	<a href="<?php echo Yii::app()->createUrl('identity/create', array())?>"><div id="button-box-admin"> </div></a>
	<h1>Manage Users</h1>
</div>

<div class="admin-list">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'identity-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'summaryText'=>' ',
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css', 'header'=> ''),
		'cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css',
		'htmlOptions' => array('class' => 'gridStyle'),
		'columns'=>array(
			'name',
			'last_name',
			'username',
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}',
				'updateButtonOptions' => array('class' => 'update-button'),
				'deleteButtonOptions' => array('class' => 'delete-button')
			),
		),
	)); ?>
</div>
