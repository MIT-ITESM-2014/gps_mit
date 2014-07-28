
<div class="headers">
	<a href="<?php echo Yii::app()->createUrl('identityCompany/create', array('company_id'=>$company_id))?>"><div id="button-box-admin"> </div></a>
	<h1>Users in Fleet</h1>
</div>

<div class="admin-list">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'identity-company-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css', 'header' => ' '),
	'cssFile' => Yii::app()->baseUrl . '/css/gridViewCompass.css',
	'summaryText'=>' ',
	'htmlOptions' => array('class' => 'gridStyle'),
	'columns'=>array(
	  array(
	    /*'name'=>'User',*/
	    /*'header'=>'User',
	    'value'=>'$data->identity->name." ".$data->identity->last_name',*/
	    /*'name'=>'identity.fullname',*/
	    'header'=>'User',
	    'name'=>'fullname_search',
	    'value'=>'$data->identity->fullname',
	  ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
			'updateButtonImageUrl' => "",//Yii::app()->baseUrl . '/public/images/icons/' . 'Edit.png',
			'updateButtonOptions' => array('class' => 'update-button'),
			'deleteButtonImageUrl' => Yii::app()->baseUrl . '/public/images/icons/' . 'DeleteColor.png',
			'deleteButtonOptions' => array('class' => 'delete-button')
		),
	),
)); ?>
</div>
