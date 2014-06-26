<?php
/* @var $this TokenController */
/* @var $model Token */

$this->breadcrumbs=array(
	'Tokens'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Token', 'url'=>array('index')),
	array('label'=>'Create Token', 'url'=>array('create')),
	array('label'=>'View Token', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Token', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Update Token <?php echo $model->id; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>