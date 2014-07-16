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
          if(data.truck.name != null)
          {
            $("#trucks_truck_name").html(data.truck.name);
          }
          if(data.truck.total_distance != null)
          {
            //added units and trimmed results
            var total_distance_trimmed = Math.round(data.truck.total_distance*100)/100;
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
            var average_speed_trimmed = Math.round(data.truck.average_speed*100)/100;
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
            var distance_between_short_stops_trimmed = Math.round(data.truck.average_distance_between_short_stops*100)/100;
            var distance_between_short_stops_trimmed_string = distance_between_short_stops_trimmed.toString().concat(" km");
            $("#trucks_truck_stats_data_average_distance_between_short_stops").html(distance_between_short_stops_trimmed_string);   
          }
          if(data.truck.average_stem_distance != null)
          {
            var average_stem_distance_trimmed = Math.round(data.truck.average_stem_distance*100)/100;
            var average_stem_distance_trimmed_string = average_stem_distance_trimmed.toString().concat(" km");
            $("#trucks_truck_stats_data_average_stem_distance").html(average_stem_distance_trimmed_string);  
          }              
        }
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.statusText);
      alert(thrownError);
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

        chart_1_1_params ={
          chart: {
            backgroundColor: '#efefef',
            type: 'column'
          },
          title: {
            text: 'Short Stops Duration'
          },
          xAxis: {
            categories: ['BDPD-24'],
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Time distribution'
            }
          },
          tooltip: {
            headerFormat: '<span style="font-size:28px"><b>{point.key}</span><table><br/    > ',
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
              color: '#139B83',
              data: short_stops_ranges_data_stops_0_5,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '5-15 min',
              color: '#042E3C',
              data: short_stops_ranges_data_stops_5_15,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '15-30 min',
              data: short_stops_ranges_data_stops_15_30,                
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '30 min-1 hr',
              data: short_stops_ranges_data_stops_30_1,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '1-2 hr',
              data: short_stops_ranges_data_stops_1_2,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            },
            {
              name: '2+ hrs',
              data: short_stops_ranges_data_stops_2_plus,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                valueSuffix: ' stops ' 
              }
            }
          ]
        };
  
        chart_1_params = {
          chart: {
            backgroundColor: '#efefef',
            font: '"Ubuntu Medium", Arial, Helvetica, sans-serif',
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
          },
          title: {
            text: 'Stops Analysis',
            align: 'center',
            y: 25
          },
          exporting: {
            buttons:{
              contextButton:{
                symbol: 'square',
                symbolFill: '#003c4b',
                symbolSize: 18,
                symbolStrokeWidth: 0
              }           
            }
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
            data: [
              ['Short Stop', 5],
              ['Long Stop', 6],
              ['Traveling', 10]
            ],
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
      alert(xhr.statusText);
      alert(thrownError);
    }   
  });
}




/* Change truck chart navigation*/

var change_chart_button_truck = $('#change_chart_button_truck');
change_chart_button_truck.click(function(){
  if(chart_status==0)
  {
    displayShortStopsAnalysis();
    change_chart_button_truck.html('Use Analysis');
    $('#container').highcharts(chart_1_1_params);
    chart_status = 1;
  }
  else if(chart_status==1)
  {
    displayStopsAnalysis();
    change_chart_button_truck.html('Short Stops Analysis');
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
        
        chart_2_params = {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Time in short stops'
          },
          xAxis: {
            categories: chart_2_params_categories
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
            column: {
              stacking: 'percent'
            }
          },
          series: [{
                name: '0-5 min',
                color: '#139B83',
                data: chart_2_params_series.chart_2_params_series_0_5,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
            }, {
                name: '5-15 min',
                color: '#042E3C',
                data: chart_2_params_series.chart_2_params_series_5_15,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
            }, {
                name: '15-30 min',
                data: chart_2_params_series.chart_2_params_series_15_30,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
                }, {
                name: '30 min-1:00 hr',
                data: chart_2_params_series.chart_2_params_series_30_1,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
                }, {
                name: '1:00-2:00 hr',
                data: chart_2_params_series.chart_2_params_series_1_2,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
                }, {
                name: '2:00+ hr',
                data: chart_2_params_series.chart_2_params_series_2_plus,
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                    valueSuffix: ' stops ' 
                    }
            }]
        };
        
        chart_3_params = {
          chart: {
              type: 'column'
          },
          title: {
              text: 'Time distribution'
          },
          xAxis: {
              categories: chart_2_params_categories
          },
          yAxis: {
              min: 0,
              title: {
                  text: 'Time distribution'
              }
          },
          tooltip: {
              headerFormat: '<span style="font-size:20px"><b>{point.key}</span><table><br/> ',
              shared: true,
          },
          plotOptions: {
              column: {
                  stacking: 'percent'
              }
          },
              series: [{
              name: 'Traveling',
              color: '#139B83',
              data: chart_3_params_series.chart_3_params_series_traveling,
              tooltip: {
                  pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                  valueSuffix: ' min ' 
                  }
          }, {
              name: 'Short stop',
              color: '#042E3C',
              data: chart_3_params_series.chart_3_params_series_short_stop,
              tooltip: {
                  pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                  valueSuffix: ' min ' 
                  }
          }, {
              name: 'Long stop',
              data: chart_3_params_series.chart_3_params_series_long_stop,
              tooltip: {
                  pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y}<b>({point.percentage:.1f}%)</b><br/>',
                  valueSuffix: ' min ' 
                  }
          }]
        };
        
        chart_4_params = {
          chart: {
            type: 'column',
            margin: 75,
            options3d: {
              enabled: true,
              alpha: 15,
              beta: 5,
              depth: 70
            }
          },

          title: {
            text: 'General Stats',
          },
          plotOptions: {
            column: {
              depth: 25
            }
          },
          xAxis: {
            categories: chart_2_params_categories,
          },
          yAxis: {
            opposite: false,
            /*title: {
                text: 'Number of stops'
            }*/
          },
          tooltip: { 
            headerFormat: '<span style="font-size:20px"><b>{point.key}</span><br/>',
            valueDecimals: 2,
            shared: true
          },  
          series: [
            {
              name: 'Average number of short stops',
              color: '#139B83',
              data: chart_4_params_categories_series.average_short_stops_count,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' <br/>' 
              }
            },
            {
              name: 'Average short stop duration',
              color: '#042E3C',
              data: chart_4_params_categories_series.average_short_stops_duration,
              visible: false,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' min<br/>' 
              }             
            },
            {
              name: 'Average Distance',
              data: chart_4_params_categories_series.average_distance,
              visible: false,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' km<br/>',
              }   
            },
            {
              name: 'Average Speed',
              data: chart_4_params_categories_series.average_speed,
              visible: false,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' km/hr<br/>'
              }
            },
            {
              name: 'Fuel consumption per km',
              data: chart_4_params_categories_series.fuel_consumption_per_km,
              visible: false,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b>',
                valueSuffix: ' Lts<br/>'
              }
            },
            {
              name: 'Total number of trips',
              data: chart_4_params_categories_series.number_of_trips,
              visible: false,
              tooltip: {
                pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y:,.0f}</b>',
                valueSuffix: ' <br/>'
              }
            }
          ]
        };//end chart_4
        
        chart_slider_1 = ChartSlider('#trucks-slider-chart-container','#trucks-slider-left-arrow','#trucks-slider-right-arrow')
        chart_slider_1.addElement(chart_2_params);
        chart_slider_1.addElement(chart_3_params);
        chart_slider_1.addElement(chart_4_params);
        chart_slider_1.start();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.statusText);
      alert(thrownError);
    }   
  });

/*Create chart_1*/
Highcharts.setOptions({
  colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
  lang: {
      drillUpText: '<--', 
  }
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




