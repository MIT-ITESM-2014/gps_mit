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

Yii::app()->clientScript->registerScript('submitForm', "
$('#button-box').click( function(){
  $('#identity-form input').click();
});");
?>

<div id="create-header">
	<div id="button-box" onclick="'submitForm();'"> </div>
	<h1>Create Identity</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
