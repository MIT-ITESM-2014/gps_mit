<?php
/* @var $this IdentityController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/maps/google_maps_style_test.js');

$this->breadcrumbs=array(
	'Identities',
);

$this->menu=array(
	array('label'=>'Create Identity', 'url'=>array('create')),
	array('label'=>'Manage Identity', 'url'=>array('admin')),
);
?>

<h1>Identities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<h4> Map made with javascript and jquery</h4>

<div id="map-canvas" style="height: 600px; width: 800px;">
</div>

<div id="map-legend" style=" background: white; padding: 10px; color: #EB3B45; border: 2px solid black; ">
</div>