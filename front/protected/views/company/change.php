
<div id="change-fleet-view-container"> 
<div id="select-fleet-container">
	<div id="select-fleet-image"> </div>
	<div id="select-fleet-text"> Select a fleet to continue. </div>
</div>

<div class="choose-fleet-list-container">
	<div class="choose-fleet-filter-background">
		<div class="select-fleet-search-icon"></div>
	</div>
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'identity-company-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'emptyText'=>"You don't have any fleets available.",
		'summaryText'=>' ',
		'htmlOptions' => array('class' => 'gridStyle'),
		'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/changeCompany.css', 'header' => ' '),	
		'cssFile' => Yii::app()->baseUrl . '/css/changeCompany.css',
		'columns'=>array(
		  array(
	      'header'=>'Fleet Name',
	      'name'=>'company_name_search',
	      'value'=>'$data->company->name',
	    ),
			array(
				'class'=>'CButtonColumn',
				'template'=>'{select}',
				'buttons'=>array(
				  'select' => array(
				    'label'=>' ',
				    'options' => array('class'=> 'go-to-map-button'),
				    'url'=>'Yii::app()->createUrl("company/change", array("company"=>$data->company_id))',
				  ),
				),
			),
		),
	)); ?>
</div>
<?php 
  if($company_in_process == 1)
    echo '<div id="warning_company_in_process"> The fleet '.$company_in_process_name.' information is being process at the moment. Please try again later.</div>';
?>
<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/fleets/actions.js',CClientScript::POS_END);
?>

</div>
