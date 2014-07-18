<?php
/* @var $this IdentityCompanyController */
/* @var $model IdentityCompany */

$this->breadcrumbs=array(
	'Identity Companies'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List IdentityCompany', 'url'=>array('index')),
	array('label'=>'Create IdentityCompany', 'url'=>array('create')),
	array('label'=>'Update IdentityCompany', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IdentityCompany', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IdentityCompany', 'url'=>array('admin')),
);
?>

<h1>View IdentityCompany #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'identity_id',
		'company_id',
	),
)); ?>
