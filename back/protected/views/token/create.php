<?php
/* @var $this TokenController */
/* @var $model Token */

$this->breadcrumbs=array(
	'Tokens'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Token', 'url'=>array('index')),
	array('label'=>'Manage Token', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Create Token</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>