<?php
/* @var $this IdentityController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Identities',
);

$this->menu=array(
	array('label'=>'Create Identity', 'url'=>array('create')),
	array('label'=>'Manage Identity', 'url'=>array('admin')),
);
?>

<div class="headers">
	<h1>Users</h1>
</div>

<div id="list-contents">
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
</div>