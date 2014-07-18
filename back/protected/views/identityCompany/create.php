<?php
/* @var $this IdentityCompanyController */
/* @var $model IdentityCompany */

$this->breadcrumbs=array(
	'Identity Companies'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IdentityCompany', 'url'=>array('index')),
	array('label'=>'Manage IdentityCompany', 'url'=>array('admin')),
);
?>

<h1>Create IdentityCompany</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'dropdown_data'=>$dropdown_data)); ?>
