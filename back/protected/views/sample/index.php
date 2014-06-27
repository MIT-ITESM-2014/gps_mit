<?php
/* @var $this SampleController */
/* @var $dataProvider CActiveDataProvider */

/*$this->breadcrumbs=array(
	'Samples',
);

$this->menu=array(
	array('label'=>'Create Sample', 'url'=>array('create')),
	array('label'=>'Manage Sample', 'url'=>array('admin')),
);*/
?>

<div class="headers">
	<h1>Samples</h1>
</div>
<?php echo CHtml::link('Link Function de detección de rutas y puntos',array('sample/findStopsAndRoutes')); ?>
<div id="list-contents">
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
</div>
