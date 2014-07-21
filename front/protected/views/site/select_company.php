<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'identity-company-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
	  array(
	    /*'name'=>'User',*/
	    /*'header'=>'User',
	    'value'=>'$data->identity->name." ".$data->identity->last_name',*/
	    /*'name'=>'identity.fullname',*/
	    'header'=>'Company Name',
	    /*'name'=>'fullname_search',*/
	    'value'=>'$data->company->name',
	  ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{select}',
			'buttons'=>array(
			  'select' => array(
			    'label'=>'Select',
			    //'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
			    'url'=>'Yii::app()->createUrl("company/change", array("company"=>$data->company_id))',
			  ),
			),
		),
	),
)); ?>
