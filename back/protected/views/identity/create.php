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

<div id="create-header">
	<h1>Create Identity</h1>
	<div id="button-box"> 
	   <?php //echo CHtml::linkButton($model->isNewRecord ? 'CREATE' : 'Save', array('class'=>'button-style'))?>
	</div>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>