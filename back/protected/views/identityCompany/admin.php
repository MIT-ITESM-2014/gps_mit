<?php
/* @var $this IdentityCompanyController */
/* @var $model IdentityCompany */


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#identity-company-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="headers">
	<a href="<?php echo Yii::app()->createUrl('identityCompany/create', array('company_id'=>$company_id))?>"><div id="button-box-admin"> </div></a>
	<h1></h1>
</div>

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
	    'header'=>'User',
	    'name'=>'fullname_search',
	    'value'=>'$data->identity->fullname',
	  ),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
		),
	),
)); ?>
