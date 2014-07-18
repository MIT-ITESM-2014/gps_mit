<?php
/* @var $this IdentityCompanyController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Identity Companies',
);

$this->menu=array(
	array('label'=>'Create IdentityCompany', 'url'=>array('create')),
	array('label'=>'Manage IdentityCompany', 'url'=>array('admin')),
);
?>

<h1>Identity Companies</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
