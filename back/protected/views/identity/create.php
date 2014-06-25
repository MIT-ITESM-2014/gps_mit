<?php
/* @var $this IdentityController */
/* @var $model Identity */

/*$this->breadcrumbs=array(
	'Identities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Identity', 'url'=>array('index')),
	array('label'=>'Manage Identity', 'url'=>array('admin')),
);*/
?>

<div id="">
	<h1>Create Identity</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>