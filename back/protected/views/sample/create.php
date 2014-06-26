<?php
/* @var $this SampleController */
/* @var $model Sample */

$this->breadcrumbs=array(
	'Samples'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Sample', 'url'=>array('index')),
	array('label'=>'Manage Sample', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Create Sample</h1>
</div>

<div id="data" name="data">

  <?php
    $this->renderPartial('_ajaxContent',array('step'=>$step));
  ?>
</div>

