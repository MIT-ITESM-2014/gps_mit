<div id="stats-data-container">
  <div id="stats-data-title"> 
    <div id="stats-title-label"> General Stats for Fleet</div> 
  </div>
  
  <div class="clear"> </div>

  <div id="stats-data-left">
    <div id="stats-data-total-trips-container">
      <div id="stats-trips-icon"> </div>
      <div class="stats-data-label">Total trips</div>
      <div class="stats-data-data"></div>
    </div>
    <div id="stats-data-distance-traveled">
      <div id="stats-distance-traveled-icon"></div>
      <div class="stats-data-label">Distance traveled</div>
      <div class="stats-data-data"></div>
    </div>
    <div id="stats-data-average-short-stop-duration">
      <div id="stats-average-short-stop-duration-icon"></div>
      <div class="stats-data-label">Average stop duration</div>
      <div class="stats-data-data"></div>
    </div>
   <!-- <div id="stats-data-total-fuel-consumption">
      <div class="stats-data-label">Total fuel consumption</div>
      <div class="stats-data-data"></div>
    </div> -->
  </div>

  <div id="stats-data-right">
    <div id="stats-right-title">dasdasd</div>
  </div>

</div>
<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/stats/actions.js',CClientScript::POS_END);
?>
