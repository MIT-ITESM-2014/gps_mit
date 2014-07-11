  <div id="truck-selection"> 
    
    <div id="selector-truck">
      <div id="truck-icon"> </div>
      <div id="truck-selector-container">
        <select id="truck_selector" name="truck_selector">
        </select>
        <div id="truck-dropdown-arrow"></div>
      </div>
    </div>
    <div id="selector-day">
      <div id="day-icon"> </div>
      <div id="date-route" name="date-route">
      </div>
    </div>
    <div id="selector-route">
      <div id="route-icon"> </div>
      <div id="route-selector-container">
        <select id="select-route" name="select-route"> 
        </select>
      </div>
    </div>
    
    <div id="button_update_map" name="button_update_map" class="update-button-map">
      <p id ="update-map-text"> Update </p>
    </div>
    
  </div>

<?php

$options = "";
foreach ($trucks as $t)
{
  $options = $options . " newOption = $('<option value=\"".$t->id."\">".$t->name."</option>');  $('#truck_selector').append(newOption);";
}

?>

<div id="map-canvas" >
</div>

<div id="map-legend">
  

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
              'htmlOptions'=>array(
                    'placeholder'=> 'Choose a date',
                    'class'=>'datePicker', 
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

  <div id="route-information-container">
    <div id="route-information-truck-name">
    </div>
    <div id="route-information-route-id">
    </div>
    <div id="route-information-date">
    </div>
  </div>
  
  <div id="distance_container">
    <div id="distance_icon"></div>
    <div id="distance_label">
      Distance
    </div>
    <div id="distance_data_container"></div>
  </div>

  <div class="clear"> </div>

  <div id="time_container">
    <div id="time_icon"> </div>
    <div id="time_label">
    Time
    </div>
    <div id="time_data_container"></div>
  </div>

  <div class="clear"> </div>

  <div id="average_speed_container">
    <div id="average_speed_icon"></div>
    <div id="average_speed_label">
    Average speed
    </div>
    <div id="average_speed_data_container"></div>
  </div>

  <div class="clear"> </div>

  <div id="short_stops_count_container">
    <div id="short_stops_count_icon"></div>
    <div id="short_stops_count_label">
    Short stops count
    </div>
    <div id="short_stops_count_data_container"></div>
  </div>

  <div class="clear"> </div>

  <div id="more_statistics_container">
    <div id="more_statistics_button"> </div>
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
