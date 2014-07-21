
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
	    'header'=>'Company Name',
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
