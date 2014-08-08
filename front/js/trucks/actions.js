var chart_status = 0;

/*Main menu navigation*/
$('#trucks-section').addClass('active_truck');
$('#trucks-section').removeClass('trucks-section');

/*Screen block*/
$('#select-background').screen_block_init('Gris-Op90.png');
$('#select-background').screen_block_block();

$('#compare-all-trucks-background').screen_block_init('Gris-Op90.png');
$('#compare-all-trucks-background').screen_block_unblock();

/*Page navigation*/
$('#one-truck-go-button').click(function(){
  $('#select-background').screen_block_unblock();
});

$('#compare-trucks-button').click(function(){
  $('#compare-all-trucks-background').screen_block_block();
});

$('#all-trucks-button').click(function(){
  $('#compare-all-trucks-background').screen_block_block();
  $('#select-background').screen_block_unblock();
});

$('#close-compare-all-trucks').click(function(){
  $('#compare-all-trucks-background').screen_block_unblock();
});

$( "#trucks_truck_select" ).change(function() {
  updateTruckStats();
});

//hide change chart by default
$('#change_chart_button_truck').hide();

/*Declare variables for charts*/
/*var chart_1;
var chart_2;
*/
var short_stops_ranges_data_stops_0_5;
var short_stops_ranges_data_stops_5_15;
var short_stops_ranges_data_stops_15_30;
var short_stops_ranges_data_stops_30_1;
var short_stops_ranges_data_stops_1_2;
var short_stops_ranges_data_stops_2_plus;
var truck_usage;

var chart_1_1_params;
var chart_1_params;

function updateTruckStats()
{
  $.ajax({ 
    type: "GET",
    dataType: "json",
    url: "index.php?r=truck/getTruckStats&truck_id="+document.getElementById("trucks_truck_select").value,
    success: function(data){
      if(data != null)
      {
        if(data.truck != null)
        {
          //show change chart button
          $('#change_chart_button_truck').show();

          if(data.truck.name != null)
          {
            $("#trucks_truck_name").html(data.truck.name);
          }
          if(data.truck.total_distance != null)
          {
            //added units and trimmed results
            var total_distance_trimmed = Math.round(data.truck.total_distance*10)/10;
            var total_distance_trimmed_string = total_distance_trimmed.toString().concat(" km");
            $("#trucks_truck_stats_data_total_distance_traveled").html(total_distance_trimmed_string);                
          }
          if(data.truck.route_count != null)
          {
            $("#trucks_truck_stats_data_trips").html(data.truck.route_count);
          }
          if(data.truck.average_duration != null)
          {

            var average_duration_string;
            var secondsInAMinute = 60;
            var secondsInAnHour = 60 * secondsInAMinute;
            var secondsInADay = secondsInAnHour * 24;
            
            var day = Math.floor(data.truck.average_duration / secondsInADay);
            var day_string = day.toString().concat(" d ");
            
            var hourSeconds = data.truck.average_duration % secondsInADay;
            var timeHours = Math.floor(hourSeconds / secondsInAnHour);
            var hours_string = timeHours.toString().concat(" h ");

            var minuteSeconds = hourSeconds % secondsInAnHour;
            var timeMinutes = Math.floor(minuteSeconds / secondsInAMinute);
            var minutes_string = timeMinutes.toString().concat(" min");

            //validates if day is < 0 so it can be added to final string
            if(day > 0)
            {
              average_duration_string = day_string.concat(hours_string + minutes_string);
            }
            else
            {
              average_duration_string = hours_string.concat(minutes_string);
            }

            $("#trucks_truck_stats_data_average_trip_duration").html(average_duration_string);
          }
          if(data.truck.average_speed != null)
          {
            var average_speed_trimmed = Math.round(data.truck.average_speed*10)/10;
            var average_speed_trimmed_string = average_speed_trimmed.toString().concat(" km/h");
            $("#trucks_truck_stats_data_average_speed").html(average_speed_trimmed_string);
          }
          if(data.truck.average_stop_count_per_trip != null)
          {
            var stop_count_rounded = Math.round(data.truck.average_stop_count_per_trip);
            $("#trucks_truck_stats_data_average_stop_count_per_trip").html(stop_count_rounded);
          }
          if(data.truck.average_distance_between_short_stops != null)
          {
            var distance_between_short_stops_trimmed = Math.round(data.truck.average_distance_between_short_stops*10)/10;
            var distance_between_short_stops_trimmed_string = distance_between_short_stops_trimmed.toString().concat(" km");
            $("#trucks_truck_stats_data_average_distance_between_short_stops").html(distance_between_short_stops_trimmed_string);   
          }
          if(data.truck.average_stem_distance != null)
          {
            var average_stem_distance_trimmed = Math.round(data.truck.average_stem_distance*10)/10;
            var average_stem_distance_trimmed_string = average_stem_distance_trimmed.toString().concat(" km");
            $("#trucks_truck_stats_data_average_stem_distance").html(average_stem_distance_trimmed_string);  
          }
          if(data.truck.average_trip_distance != null)
          {
            var average_trip_distance_trimmed = Math.round(data.truck.average_trip_distance*10)/10;
            var average_trip_distance_string = average_trip_distance_trimmed.toString().concat(" km");
            $("#trucks_truck_stats_distance_per_trip").html(average_trip_distance_string);
          }              
        }
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      //alert(xhr.statusText);
      //alert(thrownError);
    }   
  });
      
  $.ajax({ 
    type: "GET",
    dataType: "json",
    url: "index.php?r=truck/getTruckChart1&truck_id="+document.getElementById("trucks_truck_select").value,
    success: function(data){
      if(data != null)
      {
        //chart_1.highcharts().series[0].setData(data.time_data, true);
        $('#container').highcharts(chart_1_params);
        short_stops_ranges_data_stops_0_5 = data.short_stops_ranges_data.stops_0_5;
        short_stops_ranges_data_stops_5_15 = data.short_stops_ranges_data.stops_5_15;
        short_stops_ranges_data_stops_15_30 = data.short_stops_ranges_data.stops_15_30;
        short_stops_ranges_data_stops_30_1 = data.short_stops_ranges_data.stops_30_1;
        short_stops_ranges_data_stops_1_2 = data.short_stops_ranges_data.stops_1_2;
        short_stops_ranges_data_stops_2_plus = data.short_stops_ranges_data.stops_2_plus;
        truck_usage = data.time_data;

        chart_1_1_params ={
          credits: {
            enabled: false,
          },
          chart: {
            backgroundColor: '#efefef',
            type: 'column'
          },
          title: {
            text: 'Stops Duration'
          },
          xAxis: {
            labels:{
              enabled: false
            }
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Time distribution'
            }
          },
          tooltip: {
            headerFormat: '',
            shared: false,
          },
          plotOptions: {
            column: {
              stacking: 'percent'
            }
          },
          series: [
            {
              name: '0-5 min',
              color: '#684bb2',
              data: short_stops_ranges_data_stops_0_5,
              tooltip: {
                pointFormat: '{series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '5-15 min',
              color: '#00b27d',
              data: short_stops_ranges_data_stops_5_15,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '15-30 min',
              color: '#efbb1e',
              data: short_stops_ranges_data_stops_15_30,                
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '30 min-1 hr',
              color: '#e27331',
              data: short_stops_ranges_data_stops_30_1,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '1-2 hrs',
              color: '#df3e3c',
              data: short_stops_ranges_data_stops_1_2,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '2+ hrs',
              color: '#044e73',
              data: short_stops_ranges_data_stops_2_plus,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            }
          ]
        };
  
        chart_1_params = {
          credits: {
            enabled: false,
          },
          chart: {
            backgroundColor: '#efefef',
            font: '"Ubuntu", Arial, Helvetica, sans-serif',
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
          },
          title: {
            text: 'Stops Analysis',
            align: 'center',
            y: 25
          },      
          credits: {
            enabled: false  
          },
          plotOptions: {
            pie: {
              dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                  fontWeight: 'bold',
                  color: 'white',
                  textShadow: '0px 1px 2px black'
                }
              },
              startAngle: -90,
              endAngle: 90,
              center: ['50%', '75%']
            }
          },
          plotOptions: {
            series: {
              borderWidth: 0,
            }
          },
          series: [{
            data: truck_usage,
              type: 'pie',
              innerSize: '50%',
              colorByPoint: true,
              tooltip: {
                headerFormat: ' ' ,
                pointFormat: '<span style="font-size:80px"></span>{point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' hrs ' 
              }
          }],
        };
        if(chart_status == 0)
          $('#container').highcharts(chart_1_params);
        else if(chart_status == 1)
          $('#container').highcharts(chart_1_1_params);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      //alert(xhr.statusText);
      //alert(thrownError);
    }   
  });
}




/* Change truck chart navigation*/

var change_chart_button_truck = $('#change_chart_button_truck');
change_chart_button_truck.click(function(){
  if(chart_status==0)
  {
    displayShortStopsAnalysis();
    change_chart_button_truck.html('Usage Analysis');
    $('#container').highcharts(chart_1_1_params);
    chart_status = 1;
  }
  else if(chart_status==1)
  {
    displayStopsAnalysis();
    change_chart_button_truck.html('Stops Analysis');
    $('#container').highcharts(chart_1_params);
    chart_status = 0;
  }
});

function displayShortStopsAnalysis()
{
  $('#container').highcharts(chart_1_params);
}

function displayStopsAnalysis()
{
  $('#container').highcharts(chart_1_1_params);
}

/*Load data for trucks charts*/
var chart_2_params_categories;
var chart_2_params_series;
var chart_3_params_series;
var chart_4_params_categories_series;
var chart_4_new_params_series;
var chart_2_params;
var chart_3_params;
var chart_4_params;
var chart_slider_1;

$.ajax({ 
    type: "GET",
    dataType: "json",
    url: "index.php?r=truck/getTrucksChartsInfo",
    success: function(data){
      if(data != null)
      {
      
        chart_2_params_categories = data.chart_2_params_categories;
        chart_2_params_series = data.chart_2_params_series;
        chart_3_params_series = data.chart_3_params_series;
        chart_4_params_categories_series = data.chart_4_params_categories_series;
        chart_4_new_params_series = data.chart_4_new_params_series;
        chart_5_params_categories_series = data.chart_5_params_categories_series;
        var colors = ['#e51c23', '#f06292', '#ba68c8', '#3f51b4', '#72d572', '#ffff00', '#ffaf4c', '#a1887f', '#9e9e9e', '#b0120a', '#e91e63', '#9c27b0', '#1a237e', '#259b24', '#ffe04c', '#ff9800', '#795548', '#424242', '#f36c60', '#880e4f', '#4a148c', '#91a7ff', '#0d4502', '#ffc107', '#e65100', '#3e2723', '#b0bec5', '#9575cd', '#5677fc', '#88ee7b', '#ff6f00', '#ffab91', '#607d8b'];

        chart_2_params = {
          credits: {
            enabled: false,
          },
          chart: {
            type: 'area'
          },
          title: {
            text: 'Time in stops'
          },
          xAxis: {
            categories: chart_2_params_categories,
            labels:{
              enabled: false,
            }
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Time distribution'
            }
          },
          tooltip: {
            headerFormat: '<span style="font-size:28px"><b>{point.key}</span><table><br/    > ',
            shared: true,
          },
          plotOptions: {
            area: {
              stacking: 'percent'
            }
          },
          series: [{
                name: '0-5 min',
                color: '#684bb2',
                data: chart_2_params_series.chart_2_params_series_0_5,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
            }, {
                name: '5-15 min',
                color: '#00b27d',
                data: chart_2_params_series.chart_2_params_series_5_15,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
            }, {
                name: '15-30 min',
                color: '#efbb1e',  
                data: chart_2_params_series.chart_2_params_series_15_30,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
                }, {
                name: '30 min-1:00 hr',
                color: '#e27331',                
                data: chart_2_params_series.chart_2_params_series_30_1,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
                }, {
                name: '1:00-2:00 hr',
                color: '#df3e3c',
                data: chart_2_params_series.chart_2_params_series_1_2,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
                }, {
                name: '2:00+ hr',
                color: '#044e73',      
                data: chart_2_params_series.chart_2_params_series_2_plus,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
            }]
        };
        
        chart_3_params = {
          credits: {
            enabled: false,
          },
          chart: {
              type: 'area'
          },
          title: {
              text: 'Time distribution'
          },
          xAxis: {
              categories: chart_2_params_categories,
              labels: {
                enabled: false
              }
          },
          yAxis: {
              min: 0,
              max: 100,
              title: {
                  text: 'Percent'
              }
          },
          tooltip: {
              headerFormat: '<span style="font-size:20px"><b>{point.key}</span><table><br/> ',
              shared: true,
          },
          plotOptions: {
              area: {
                  stacking: 'percent'
              }
          },
              series: [{
              name: 'Traveling',
              color: '#006161',
              data: chart_3_params_series.chart_3_params_series_traveling,
              tooltip: {
                  pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                  valueSuffix: ' min ' 
                  }
          }, {
              name: 'Stop',
              color: '#4acfaf',
              data: chart_3_params_series.chart_3_params_series_short_stop,
              tooltip: {
                  pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                  valueSuffix: ' min ' 
                  }
          }, {
              name: 'Idle',
              color: '#00a995',
              data: chart_3_params_series.chart_3_params_series_long_stop,
              tooltip: {
                  pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                  valueSuffix: ' min ' 
                  }
          }]
        };
        
        chart_4_params = {
          credits: {
            enabled: false,
          },
          chart: {
            type: 'scatter',
            zoom: 'xy'
          },
          title: {
            text: 'General Stats',
          },
          plotOptions: {
            scatter: {
              marker: {
                radius: 6,
                states: {
                  hover: {
                    enabled: true,
                    lineColor: 'rgb(100, 100, 100)'
                  }
                }
              },
              states: {
                hover: {
                  marker: {
                    enabled: false
                  }
                }
              }
            }
          },
          legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 40,
            y: 20,
            floating: true,
            backgroundColor:'#FFFFFF',
            borderWidth: 1
          },
          xAxis: {
            title: {
              enabled: true, 
              text: 'Distance of Trip (km)'
            },
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true
          },
          yAxis: {
            opposite: false,
            title: {
                enabled: false
                //text: 'Number of stops'
            }
          },
          tooltip: { 
           formatter: function() {
              return '<b>' + this.point.myData + '</b>' + '<br/>' + 'Distance: ' +  this.point.x +' km <br/>' + this.series.name + ': ' +this.point.y;
            },
            shared: true
          },  
          series: [
            {
              name: 'Average Speed',
              data: chart_4_new_params_series.chart_4_data_speed,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' km/h <br/>' 
              }
            },
            {
              name: 'STEM Distance',
              data: chart_4_new_params_series.chart_4_data_stem,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' km <br/>' 
              }
            },           
          ]
        };//end chart_4

        chart_5_params = {
          credits: {
            enabled: false,
          },
          chart: {
            type: 'scatter',
            zoom: 'xy'
          },
          title: {
            text: 'General Stats',
          },
          plotOptions: {
            scatter: {
              marker: {
                radius: 6,
                states: {
                  hover: {
                    enabled: true,
                    lineColor: 'rgb(100, 100, 100)'
                  }
                }
              },
              states: {
                hover: {
                  marker: {
                    enabled: false
                  }
                }
              }
            }
          },
          legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 40,
            y: 20,
            floating: true,
            backgroundColor:'#FFFFFF',
            borderWidth: 1
          },
          xAxis: {
            title: {
              enabled: true, 
              text: 'Time of Trip (hrs)'
            },
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true
          },
          yAxis: {
            opposite: false,
            title: {
                enabled: false
            }
          },
          tooltip: { 
           formatter: function() {
              return '<b>' + this.point.myData + '</b>' + '<br/>' + 'Time: ' +  this.point.x +' hrs <br/>' + this.series.name + ': ' + this.point.y;
            },
            shared: true
          },
          series: [
            {
              name: 'Average Speed',
              data: chart_5_params_categories_series.chart_5_data_average_speed,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' km/h <br/>' 
              }
            },
            {
              name: 'Time in Stops',
              data: chart_5_params_categories_series.chart_5_data_time_short_stops,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' hrs <br/>' 
              }
            },
            {
              name: 'Time Traveling ',
              data: chart_5_params_categories_series.chart_5_data_time_traveling,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' hrs <br/>' 
              }
            },
            {
              name: 'Stops ',
              data: chart_5_params_categories_series.chart_5_data_no_short_stops,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                //valueSuffix: ' s <br/>' 
              }
            },
            {
              name: 'Distance ',
              data: chart_5_params_categories_series.chart_5_data_total_distance_traveled,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' km <br/>' 
              }
            },
          ]  
        }; //chart 5
        
        chart_slider_1 = ChartSlider('#trucks-slider-chart-container','#trucks-slider-left-arrow','#trucks-slider-right-arrow')
        chart_slider_1.addElement(chart_2_params);
        chart_slider_1.addElement(chart_3_params);
        chart_slider_1.addElement(chart_4_params);
        chart_slider_1.addElement(chart_5_params);
        chart_slider_1.start();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      //alert(xhr.statusText);
      //alert(thrownError);
    }   
  });

/*Create chart_1*/
Highcharts.setOptions({
  colors: ['#e51c23', '#f06292', '#ba68c8', '#3f51b4', '#72d572', '#ffff00', '#ffaf4c', '#a1887f', '#9e9e9e', '#b0120a', '#e91e63', '#9c27b0', '#1a237e', '#259b24', '#ffe04c', '#ff9800', '#795548', '#424242', '#f36c60', '#880e4f', '#4a148c', '#91a7ff', '#0d4502', '#ffc107', '#e65100', '#3e2723', '#b0bec5', '#9575cd', '#5677fc', '#88ee7b', '#ff6f00', '#ffab91', '#607d8b'],
});
/*
chart_1 = $('#container').highcharts(chart_1_params);
chart_2 = $('#container').highcharts(chart_1_params);
*/



/*Charts slider*/
function ChartSlider (container_id, left_arrow_id, right_arrow_id) {
    this.left_arrow = $(left_arrow_id);
    this.right_arrow = $(right_arrow_id);
    this.container = $(container_id);
    this.current_status = null;
    this.element_count = 0;
    this.chart_container = null;
    this.charts_container = [];
    this.parent = this;
    
    this.moveRight = function() {
      if(this.current_status != null)
      {
        this.current_status = this.current_status + 1;
        if(this.current_status >= this.element_count)
          this.current_status = 0;
        this.showChart(this.current_status);
      }
      else
      {
        alert("There are no charts available");
      }  
    };
    
    this.moveLeft = function() {
      if(this.current_status != null)
      {
        this.current_status = this.current_status - 1;
        if(this.current_status < 0)
          this.current_status = this.element_count - 1;
        this.showChart(this.current_status);
      }
      else
      {
        alert("There are no charts available");
      }  
    };
    
    this.addElement = function(chart_params)
    {
      this.charts_container[element_count] = chart_params;
      this.element_count =  this.element_count + 1;
    };
    
    this.showChart = function(chart_number)
    {
      if(chart_number < this.element_count)
      {
        this.chart_container = this.container.highcharts(charts_container[chart_number]);
      }
    };
    
    
    this.start = function()
    {
      if(this.element_count > 0)
      {
        this.showChart(0);
        this.current_status = 0;
      }
      else
      {
        alert("There are no charts available");
      }
    };
    
    /*Navigation*/
    $(this.right_arrow).click(function(){
      parent.moveRight();
    });
    
    $(this.left_arrow).click(function(){
      parent.moveLeft();
    });
    
    return this;
}

//Selecting first truck after loading
var opt = $('#trucks_truck_select option:eq(1)').val();
$('#trucks_truck_select').val(opt);
updateTruckStats();

