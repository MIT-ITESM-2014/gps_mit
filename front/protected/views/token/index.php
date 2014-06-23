<?php
/* @var $this TokenController */
/* @var $dataProvider CActiveDataProvider */



//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/maps/g_map_test.js');


$this->breadcrumbs=array(
	'Tokens',
);

$this->menu=array(
	array('label'=>'Create Token', 'url'=>array('create')),
	array('label'=>'Manage Token', 'url'=>array('admin')),
);
?>

<h1>Tokens</h1>

<?php 
/*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/ 
?>

<h4> Map made with javascript and jquery</h4>
<?php $this->renderPartial('_map', array('script'=>$script)); ?>
