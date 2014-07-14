/*Declare variables for charts*/

$('#select-background').screen_block_init('Gris-Op90.png');

$('#select-background').screen_block_block();

$('#trucks-section').addClass('active_truck');
$('#trucks-section').removeClass('trucks-section');

$('#one-truck-go-button').click(function(){
  unblock();
});

function unblock()
{
  $('#select-background').screen_block_unblock();
}

$( "#trucks_truck_select" ).change(function() {
  updateTruckStats();
});

var data_for_chart_1 = [
  ['0 - 5 min', 126],
  ['5 - 15 min', 187],
  ['15 - 30 min', 196],
  ['30 min - 1 hr ', 40],
  ['1 hr - 2 hrs', 5],
  ['2 hr - 4 hrs', 5]
];

var chart_1;

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
              alert("I'll update the data");
              data_for_chart_1.drilldown.series.data =data;
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
          }   
      });
}



$(function () {    

    Highcharts.setOptions({
        colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
        lang: {
            drillUpText: '<--', 
        }
    });

    // Create the chart
    chart_1 = $('#container').highcharts({
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
            type: 'pie',
            innerSize: '50%',
            colorByPoint: true,
            data: [{
              name: 'Short stops',
              y: 585,
              drilldown: 'short_stops_time',
            }, 
            {
              name: 'Long Stops',
              y: 2578,
              drilldown: false
            }, 
            {
              name: 'Traveling',
              y: 1381,
              drilldown: false
            }],

            tooltip: {
              headerFormat: ' ' ,
              pointFormat: '<span style="font-size:80px"></span>{point.y}<b>({point.percentage:.1f}%)</b><br/>',
              valueSuffix: ' hrs ' 
            }
        }],

        drilldown: {
          series: [{
            type: 'column', 
            plotOptions: {
              column:{
                stacking: 'normal',
              } 
            },
            title: {
              text: 'Time spent in short stops',
            },
            id: 'short_stops_time',
            data: data_for_chart_1,
            tooltip: {headerFormat: ' ' ,
              pointFormat: '<span style="font-size:80px"></span>{point.y}<b>({point.percentage:.1f}%)</b><br/>',
              valueSuffix: ' hrs ' 
            }
          }],
          drillUpButton: {
            theme: {
              fill: '#003c4b',
              states: {
                hover:{
                  fill: '#49ceae'
                },
                select:
                {
                  fill: '#49ceae'
                },
              },
              style: {
                color: '#FFFFFF',
                fontWeight: 'bold'
              }, 
            },         
          }
        }
  })
});
