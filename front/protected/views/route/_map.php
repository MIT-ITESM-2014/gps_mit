  <div id="truck-selection"> 
    
    <div id="selector-truck" class="selector-truck">
      <div id="truck-icon"> </div>
      <div id="truck-selector-container" class="truck-selector-container">
        <select id="truck_selector" class="truck_selector" name="truck_selector">
        </select>
        <div id="truck-dropdown-arrow"></div>
      </div>
    </div>
    <div id="selector-day" class="day-selector-container">
      <div id="day-icon"> </div>
      <div id="date-route" class="date-route" name="date-route"> 
      </div>
 
 <?php      
  $this->widget('zii.widgets.jui.CJuiDatePicker', array(
              'model'=>$model,
              'attribute'=>'start_date',
              'options'   => array(
                      'changeYear' => true,
                      'dateFormat' => 'mm/dd/yy',
              ),
              'htmlOptions'=>array(
                    'placeholder'=> 'Choose a date',
                    'class'=>'datePicker',
                    'id'=>'choose_date_dp', 
        ),
      ));
?>
    </div>
    <div id="selector-route" class="selector-route">
      <div id="route-icon"> </div>
      <div id="route-selector-container" class="route-selector-container">
        <select id="select-route" class="select-route" name="select-route"> 
        </select>
      </div>
    </div>
    <div id="button_update_map" name="button_update_map" class="update-button-map">
      <p id ="update-map-text"> Go </p>
    </div>
  </div>
  <div id="truck-selection-help">
    <img id="truck-selection-help-image" src="<?php echo Yii::app()->request->baseUrl.'/public/images/truck_help.png';?>"/>
  </div>


<div id="map-canvas" >
</div>

<div id="map-legend">

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

</div>

