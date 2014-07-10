$( "#trucks_truck_select" ).change(function() {
  updateTruckStats();
});

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
                if(data.truck.total_distance != null)
                  $("#trucks_truck_stats_data_total_distance_traveled").html(data.truck.total_distance);                
                if(data.truck.route_count != null)
                  $("#trucks_truck_stats_data_trips").html(data.truck.route_count);
                if(data.truck.average_duration != null)
                  $("#trucks_truck_stats_data_average_trip_duration").html(data.truck.average_duration);
                if(data.truck.average_speed != null)
                  $("#trucks_truck_stats_data_average_speed").html(data.truck.average_speed);
                if(data.truck.average_stop_count_per_trip != null)
                  $("#trucks_truck_stats_data_average_stop_count_per_trip").html(data.truck.average_stop_count_per_trip);
                if(data.truck.average_distance_between_short_stops != null)
                  $("#trucks_truck_stats_data_average_distance_between_short_stops").html(data.truck.average_distance_between_short_stops);   
                if(data.truck.average_stem_distance != null)
                  $("#trucks_truck_stats_data_average_stem_distance").html(data.truck.average_stem_distance);                
              }
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
          }   
      });
}

$(function () {
    $('#container').highcharts({
        chart: {
            type: 'pie',
            options3d: {
				enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        credits: {
          enabled: false,
        },
        title: {
            text: 'Browser market shares at a specific website, 2014'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['Firefox',   45.0],
                ['IE',       26.8],
                {
                    name: 'Chrome',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['Safari',    8.5],
                ['Opera',     6.2],
                ['Others',   0.7]
            ]
        }]
    });
});

