

<div class="headers">
	<a href="<?php echo Yii::app()->createUrl('company/create', array())?>"><div id="button-box-admin"> </div></a>
	<h1> Manage Fleet </h1>
</div>

<div class="admin-list">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'company-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css', 'header' => ' '),
	'cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css',
	'summaryText'=>' ',
	'htmlOptions' => array('class' => 'gridStyle'),
	'columns'=>array(
		'name',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{manageIdentities}{update}{delete}',
			'updateButtonOptions' => array('class' => 'update-button'),
			'deleteButtonOptions' => array('class' => 'delete-button'),
			'buttons'=>array(
			  'manageIdentities'=> array(
			    'label'=>' ',
			    //'imageUrl'=>Yii::app()->request->baseUrl.'/images/icons/userIcon.png',
			    'options' => array('class' => 'users-button' ),
			    'url'=>'Yii::app()->createUrl("identityCompany/admin", array("company_id"=>$data->id))',
			  ),
			)
		),
	),
)); ?>
</div>
