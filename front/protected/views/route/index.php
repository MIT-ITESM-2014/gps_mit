
<?php

$options = "";
foreach ($trucks as $t)
{
  $options = $options . " newOption = $('<option value=\"".$t->id."\">".$t->name."</option>');  $('#truck_selector').append(newOption);";
}

?>


<?php 

$this->renderPartial('_map', array(
  'script'=>$script, 
  'trucks'=>$trucks, 
  'model'=>$model,
  //'min_date'=>$min_date,
  //'max_date'=>$max_date,
  //'inactive_days_string'=>$inactive_days_string,
 )); 
?>

<?php    
    Yii::app()->clientScript->registerScript('routeDropdown', "
      
      $('#choose_date_dp').change(function(){
        
        $.ajax({ 
          type: \"GET\",
          dataType: \"json\",
          url: \"index.php?r=route/getRouteList&truck_id=\"+document.getElementById(\"truck_selector\").value+\"&start_date=\"+document.getElementById(\"choose_date_dp\").value,
          success: function(data){        
            $('#select-route').find('option').remove();
            var parsed_data = $.parseJSON(data);
            $.each(parsed_data['routes'], function( index, value ) {
              $('#select-route').append('<option value=\"'+value['value']+'\">'+value['name']+'</option>');
            });
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
          }   
      });
});
      
    ");
    
    ?>

<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/routes/actions.js',CClientScript::POS_END);
?>
<?php
Yii::app()->clientScript->registerScript('addOptions', "
  var newOption;
  ".$options."
  $('#truck_selector').append(newOption);
  var opt = $('#truck_selector option:eq(1)').val();
  $('#truck_selector').val(opt); 
  updateAvailableDate();
",CClientScript::POS_END);
?>
