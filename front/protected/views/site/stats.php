<div id="stats-data-container">
  <div id="stats-data-total-trips-container">
    <div class="stats-data-label">Total trips:</div>
    <div class="stats-data-data"></div>
  </div>
  <div id="stats-data-distance-traveled">
    <div class="stats-data-label">Distance traveled:</div>
    <div class="stats-data-data"></div>
  </div>
  <div id="stats-data-average-short-stop-duration">
    <div class="stats-data-label">Average short stop duration:</div>
    <div class="stats-data-data"></div>
  </div>
  <div id="stats-data-total-fuel-consumption">
    <div class="stats-data-label">Total fuel consumption:</div>
    <div class="stats-data-data"></div>
  </div>
</div>
<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/stats/actions.js',CClientScript::POS_END);
?>
