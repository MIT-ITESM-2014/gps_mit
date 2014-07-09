<div class="headers">
	<h1>Trucks</h1>
</div>

<div id="list-contents">
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
</div>
