<?php
  echo CHtml::dropDownList('trucks_truck_select', null, $trucks, array('prompt'=>'Choose a truck'));
?>

<div id="trucks_truck_stats">
  <div id="t_total_distance_traveled_container" class="trucks_truck_stat_container">
    <div id="trucks_truck_stats_label_total_distance_traveled" class="trucks_truck_stats_label">
    Total distance traveled:
    </div>
    <div id="trucks_truck_stats_data_total_distance_traveled" class="trucks_truck_stats_data">
    </div>
  </div>

  <div id="t_trips_container" class="trucks_truck_stat_container">
    <div id="trucks_truck_stats_label_trips" class="trucks_truck_stats_label">
    Trips:
    </div>
    <div id="trucks_truck_stats_data_trips" class="trucks_truck_stats_data">
    </div>
  </div>

  <div id="t_average_trip_duration_container" class="trucks_truck_stat_container">
    <div id="trucks_truck_stats_label_average_trip_duration" class="trucks_truck_stats_label">
    Average trip duration:
    </div>
    <div id="trucks_truck_stats_data_average_trip_duration" class="trucks_truck_stats_data">
    </div>
  </div>

  <div id="t_average_speed_container" class="trucks_truck_stat_container">
    <div id="trucks_truck_stats_label_average_speed" class="trucks_truck_stats_label">
    Average speed:
    </div>
    <div id="trucks_truck_stats_data_average_speed" class="trucks_truck_stats_data">
    </div>
  </div>

  <div id="t_average_stop_count_per_trip_container" class="trucks_truck_stat_container">
    <div id="trucks_truck_stats_label_average_stop_count_per_trip" class="trucks_truck_stats_label">
    Average stop count per trip:
    </div>
    <div id="trucks_truck_stats_data_average_stop_count_per_trip" class="trucks_truck_stats_data">
    </div>
  </div>

  <div id="t_average_distance_between_short_stops_container" class="trucks_truck_stat_container">
    <div id="trucks_truck_stats_label_average_distance_between_short_stops" class="trucks_truck_stats_label">
    Average distance between short stops:
    </div>
    <div id="trucks_truck_stats_data_average_distance_between_short_stops" class="trucks_truck_stats_data">
    </div>
  </div>

  <div id="t_average_stem_distance_container" class="trucks_truck_stat_container">
    <div id="trucks_truck_stats_label_average_stem_distance" class="trucks_truck_stats_label">
    Average stem distance:
    </div>
    <div id="trucks_truck_stats_data_average_stem_distance" class="trucks_truck_stats_data">
    </div>
  </div>
  
  <div id="container" style="height: 400px"></div>

</div>

<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/trucks/actions.js',CClientScript::POS_END);
?>
