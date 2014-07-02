
<?php

$options = "";
foreach ($trucks as $t)
{
  $options = $options . " newOption = $('<option value=\"".$t->id."\">".$t->identifier."</option>');  $('#truck_selector').append(newOption);";
}

?>

<div id="map-canvas" >
</div>

<div id="map-legend">
  

  <div id="date-picker-container">
    <?php
    //echo $form->textField($model,'user_end_date'); 
      $this->widget('zii.widgets.jui.CJuiDatePicker', array(
              'model'=>$model,
              'attribute'=>'start_date',
              'options'   => array(
                      'changeYear' => true,
                      'dateFormat' => 'mm/dd/yy',
                      //'timeFormat' => '',//'hh:mm tt' default
                      'beforeShowDay'=>'js:editDays',
                      'minDate'=>$min_date,
                      'maxDate'=>$max_date,
              ),
      ));
                    
    Yii::app()->clientScript->registerScript('editDays', "
      var date_picker = $('#Sample_start_date').detach();
      date_picker.appendTo('#selector-day');
    ");
    
    Yii::app()->clientScript->registerScript('moveDatePicker', "
      
      function editDays(date) {
        var disabledDates = [".$inactive_days_string."];
        for (var i = 0; i < disabledDates.length; i++) {
          if (new Date(disabledDates[i]).toString() == date.toString()) {
            return [false,''];
          }
        }
        return [true,''];
      }
    ");
    
    Yii::app()->clientScript->registerScript('routeDropdown', "
      
      $('#Sample_start_date').change(function(){
        
        $.ajax({ 
          type: \"GET\",
          dataType: \"json\",
          url: \"index.php?r=token/getRouteList&truck_id=\"+document.getElementById(\"truck_selector\").value+\"&start_date=\"+document.getElementById(\"Sample_start_date\").value,
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
  </div>

  
  <div id="distance_container">
    <div id="distance_label">
    Distance:
    </div>
    <div id="distance_data_container">
    </div>
  </div>
  <div id="time_container">
    <div id="time_label">
    Time:
    </div>
    <div id="time_data_container">
    </div>
  </div>
  <div id="average_speed_container">
    <div id="average_speed_label">
    Average speed:
    </div>
    <div id="average_speed_data_container">
    </div>
  </div>
  <div id="short_stops_count_container">
    <div id="short_stops_count_labe">
    Short stops count:
    </div>
    <div id="short_stops_count_data_container">
    </div>
  </div>
  

  

  

</div>

<?php Yii::app()->clientScript->registerScript('start_map.js',$script, CClientScript::POS_HEAD); ?>


<?php
Yii::app()->clientScript->registerScript('addOptions', "
  var newOption;
  ".$options."
  $('#truck_selector').append(newOption);  
");
?>
