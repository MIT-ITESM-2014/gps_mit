<?php
/* @var $this IdentityCompanyController */
/* @var $model IdentityCompany */

$this->breadcrumbs=array(
	'Identity Companies'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IdentityCompany', 'url'=>array('index')),
	array('label'=>'Create IdentityCompany', 'url'=>array('create')),
	array('label'=>'View IdentityCompany', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IdentityCompany', 'url'=>array('admin')),
);
?>

<h1>Update IdentityCompany <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>