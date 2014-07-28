/*Main menu navigation*/
$('#stats-section').addClass('active_stats');
$('#stats-section').removeClass('stats-section');

/*Loading data*/
$.ajax({ 
  type: "GET",
  dataType: "json",
  url: "index.php?r=company/getCompanyStats",
  success: function(data){
    if(data != null)
    {
      $("#stats-data-total-trips-container .stats-data-data").html(data.total_trips);
      $("#stats-data-distance-traveled .stats-data-data").html(data.distance_traveled);
      $("#stats-data-average-short-stop-duration .stats-data-data").html(data.average_short_stop_duration);
      $("#stats-data-total-fuel-consumption .stats-data-data").html(data.total_fuel_consumption);
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
  url: "index.php?r=truck/getTrucksChartsInfo",
  success: function(data){
    if(data != null)
    {
      chart_1_params = {
        chart: {
            type: 'area'
          },
          title: {
            text: 'Fleet Statistics'
          },
          xAxis: {
            title: {
              text: 'Truck'
            }
          },
          yAxis: {
            min: 0,
            title: {
              text: 'Speed (km/h)'
            }
          },
          legend: {
              layout: 'vertical',
              align: 'left',
              verticalAlign: 'top',
              x: 100,
              y: 70,
              floating: true,
              backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
              borderWidth: 1
          },
          plotOptions: {
            scatter: {
                marker: {
                    radius: 5,
                    states: {
                        hover: {
                            enabled: true,
                            lineColor: 'rgb(100,100,100)'
                        }
                    }
                },
                states: {
                    hover: {
                        marker: {
                            enabled: false
                        }
                    }
                },

            }
        },
      }


    }

  }


});

/*
(function($){
 
  $.fn.center = function(){
    var element = this;
    
    //$(element).load(function(){
       
      changeCss();
       
      $(window).bind("resize", function(){
          changeCss();
      });
       
      function changeCss(){
       
          var imageHeight = $(element).height();
          var imageWidth = $(element).width();
          var windowWidth = $(window).width();
          var windowHeight = $(window).height();
       
          $(element).css({
              "position" : "absolute",
              "left" : 0,
              "top" : - 40,
              "background-color": "#003C4B",
              "opacity": "0.9",
              "filter": "alpha(opacity=40)",
              "width": windowWidth,
              "height": windowHeight - 100,
          });
          alert("acabo"+imageHeight+"**"+imageWidth+"**"+windowWidth+"**"+windowHeight);
      };
    //});
  };
 
})(jQuery);


$(function(){
  
  //$("#stats-blocking-background").center();
  
});
*/


