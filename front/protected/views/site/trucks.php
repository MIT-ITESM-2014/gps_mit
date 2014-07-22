
<div id="compare-all-trucks-background">
  <div id="compare-all-trucks-container">
    <div id="close-compare-all-trucks" class="close-compare-all-trucks"> </div>
    <div id="charts-slider-container" class="charts-slider-container">
      <div id="trucks-slider-left-arrow" class="trucks-charts-slider-arrow-left"> </div>
      <div id="trucks-slider-chart-container" class="trucks-slider-chart-container">
      contenido
      </div>
      <div id="trucks-slider-right-arrow" class="trucks-charts-slider-arrow-right"> </div>
    </div>
  </div>
</div>

<div id="select-background">
  <div id="select-view-container"> 
    <div id="one-truck-container">
      <div id="one-truck-icon"> </div>
      <div class="truck-options-title"> Choose One Truck</div>
      <div class="truck-options-text"> Get information and statistics for one truck.</div>
      <a href="#" onclick="unblock();"><div id="one-truck-go-button"></div></a>
    </div>

    <div id="or-division"></div>

    <div id="all-trucks-container">
      <div id="all-trucks-icon"></div>
      <div class="truck-options-title">Compare All Trucks</div>
      <div class="truck-options-text">Get graphs comparing information and statistics from all trucks.</div>
      <div id="all-trucks-button"></div>
    </div>  
  </div>
</div>

  <div id="routes-selection"> 
    
    <div id="selector-truck-truck-section">
      <div id="truck-icon"> </div>
      <div id="truck-selector-container-truck-section">
        <?php
          echo CHtml::dropDownList('trucks_truck_select', null, $trucks, array('prompt'=>'Choose a truck'));
        ?>
        <div id="truck-dropdown-arrow"></div>
      </div>
    </div>

    <div id="compare-trucks-button"></div>

  </div>

<div id="trucks_truck_stats">

  <div id="truck-id-trucks-section">
    <div id="trucks_truck_name" class="truck-id-text"></div>
  </div>  

  <div class="clear"> </div>

  <div id="truck-stats-left">

    <div class="trips-stats-container"> 
      <div id="truck-trips-icon"> </div>
        <div id="trucks_truck_stats_label_trips" class="trucks_truck_stats_label">
          Trips
        </div>
      <div id="trips_data_container_one">
        <div id="trucks_truck_stats_data_trips" class="trucks_truck_stats_data"> </div>
        <div class="trucks_truck_stats_mini_label">Count</div>
      </div>
      <div id="trips_data_container_two">
        <div id="trucks_truck_stats_data_average_trip_duration" class="trucks_truck_stats_data"> </div>
        <div class="trucks_truck_stats_mini_label">Avg. Duration</div>
      </div>  
      <div id="trips_data_container_three"> 
        <div id="trucks_truck_stats_data_average_stop_count_per_trip" class="trucks_truck_stats_data"></div> 
        <div class="trucks_truck_stats_mini_label">Avg. Stops</div>
      </div>
    </div>

    <div class="clear"> </div>

    <div class="speed-stats-container">
      <div id="truck-average-speed-icon"> </div> 
      <div id="trucks_truck_stats_label_average_speed" class="trucks_truck_stats_label">       
        Avg. Speed
      </div>
      <div id="trucks_truck_stats_data_average_speed" class="trucks_truck_stats_data"></div>
    </div> 

    <div class="clear"> </div> 

    <div class="distance-stats-container">
      <div id="truck-distance-traveled-icon"> </div>
      <div id="trucks_truck_stats_label_total_distance_traveled" class="trucks_truck_stats_label">
        Distance
      </div>
      <div id="distance_data_container_one">
        <div id="trucks_truck_stats_data_total_distance_traveled" class="trucks_truck_stats_data"></div>
        <div class="trucks_truck_stats_mini_label">Total</div>
      </div>  
      <div id="distance_data_container_two">
        <div id="trucks_truck_stats_distance_per_trip" class="trucks_truck_stats_data"></div>
        <div class="trucks_truck_stats_mini_label">Per Trip</div>
      </div>
      <div id="distance_data_container_three">
        <div id="trucks_truck_stats_data_average_stem_distance" class="trucks_truck_stats_data"></div>
        <div class="trucks_truck_stats_mini_label">Avg. STEM</div>
      </div>  
      <div id="distance_data_container_four">
        <div id="trucks_truck_stats_data_average_distance_between_short_stops" class="trucks_truck_stats_data"></div>
        <div class="trucks_truck_stats_mini_label">B/w Stops</div>        
      </div>  
    </div>  

  </div>
  
  <div id="truck-stats-right">
    <div id="truck-analysis-right"> 
      <div id="truck-analysis-icon"></div><div id="truck-analysis-label"> Use Analysis </div>
      <div id="change_chart_button_truck">Stops Analysis</div>
    </div>
    <div id="container" class="chart_style"></div>
  </div>

</div>

<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/trucks/actions.js',CClientScript::POS_END);
?>
