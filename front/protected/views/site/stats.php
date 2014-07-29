<div id="stats-data-container">
  <div id="stats-data-title"> 
    <div id="stats-title-label"> General Stats for <?php echo Yii::app()->user->getState('current_company_name');?></div> 
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
  </div>

  <div id="stats-data-right">
    <div id="stats-right-title">Fleet Analysis</div>
    <div id="stats-charts-container" class="stats-charts-slider-container"> 
      <div id="stats-slider-left-arrow" class="trucks-charts-slider-arrow-left"></div>
      <div id="charts-container" class="chart_style"></div>
      <div id="stats-slider-right-arrow" class="trucks-charts-slider-arrow-right"></div>
    </div>
  </div>

</div>
<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/stats/actions.js',CClientScript::POS_END);
?>
