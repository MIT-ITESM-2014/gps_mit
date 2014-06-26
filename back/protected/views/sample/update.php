<?php
/* @var $this SampleController */
/* @var $model Sample */

$this->breadcrumbs=array(
	'Samples'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Sample', 'url'=>array('index')),
	array('label'=>'Create Sample', 'url'=>array('create')),
	array('label'=>'View Sample', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sample', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Update Sample <?php echo $model->id; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>