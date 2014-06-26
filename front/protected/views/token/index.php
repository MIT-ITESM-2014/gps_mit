<?php
/* @var $this TokenController */
/* @var $dataProvider CActiveDataProvider */



//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/maps/g_map_test.js');


//$this->breadcrumbs=array(
//	'Tokens',
//);

//$this->menu=array(
//	array('label'=>'Create Token', 'url'=>array('create')),
//	array('label'=>'Manage Token', 'url'=>array('admin')),
//);
?>


<?php
/*
$this->widget('zii.widgets.jui.CJuiDatePicker',array(
    'name'=>'datepicker',
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;background-color:green;color:white;',
    ),
));
*/
?>

<?php
/*
Yii::app()->clientScript->registerScript('editDays', "
  jQuery('#start_date').datepicker({
    'changeYear':true,
    'dateFormat':'mm/dd/yy',
    //'beforeShowDay': $.datepicker.noWeekends,
    'beforeShowDay': editDays,
   });
   function editDays(date) {
   var disabledDates = ['03/31/2014', '03/13/2014', '03/19/2014'];
   for (var i = 0; i < disabledDates.length; i++) {
     if (new Date(disabledDates[i]).toString() == date.toString()) {
      return [false,''];
     }
   }
   return [true,''];
  }
");
*/
?>

<?php


?>

<?php 
/*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/ 
?>

<?php 
$this->renderPartial('_map', array(
  'script'=>$script, 
  'trucks'=>$trucks, 
  'model'=>$model,
  'min_date'=>$min_date,
  'max_date'=>$max_date,
  'inactive_days_string'=>$inactive_days_string,
 )); 
?>

