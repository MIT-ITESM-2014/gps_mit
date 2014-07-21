
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'identity-company-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'summaryText'=>' ',
	'htmlOptions' => array('class' => 'gridStyle'),
	'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/changeCompany.css', 'header' => ' '),	
	'cssFile' => Yii::app()->baseUrl . '/css/changeCompany.css',
	'columns'=>array(
	  array(
	    'header'=>'Fleet Name',
	    'value'=>'$data->company->name',
	  ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{select}',
			'buttons'=>array(
			  'select' => array(
			    'label'=>'Select',
			    'url'=>'Yii::app()->createUrl("company/change", array("company"=>$data->company_id))',
			  ),
			),
		),
	),
)); ?>
