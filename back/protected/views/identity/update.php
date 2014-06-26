<?php
/* @var $this IdentityController */
/* @var $model Identity */

$this->breadcrumbs=array(
	'Identities'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Identity', 'url'=>array('index')),
	array('label'=>'Create Identity', 'url'=>array('create')),
	array('label'=>'View Identity', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Identity', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Update Identity <?php echo $model->id; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>