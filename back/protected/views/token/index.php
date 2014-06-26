<?php
/* @var $this TokenController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tokens',
);

$this->menu=array(
	array('label'=>'Create Token', 'url'=>array('create')),
	array('label'=>'Manage Token', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Tokens</h1>
</div>

<div id="list-contents">
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
</div>
