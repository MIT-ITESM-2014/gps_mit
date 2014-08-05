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

var chart_1_params; //average speed chart
var chart_2_params; //stem distance chart
var chart_3_params; //trip distance chart
var chart_4_params; //stops chart
var chart_5_params; //time traveling chart
var chart_6_params; //
var chart_7_params; //
var chart_slider_1;
var chart_status = 0;

$.ajax({ 
  type: "GET",
  dataType: "json",
  url: "index.php?r=company/getCompanyStats",
  success: function(data){
    if(data != null)
    {
      chart_1_params_x_axis = data.chart_1_params_x_axis;
      chart_1_spline_data = data.chart_1_spline_data;
      chart_1_scatter_data = data.chart_1_scatter_data;
      chart_1_line_data = data.chart_1_line_data;
      chart_2_params_x_axis = data.chart_1_params_x_axis;
      chart_2_spline_data = data.chart_2_spline_data;
      chart_2_scatter_data = data.chart_2_scatter_data;
      chart_2_line_data = data.chart_2_line_data;
      chart_3_params_x_axis = data.chart_1_params_x_axis;
      chart_3_spline_data = data.chart_3_spline_data;
      chart_3_scatter_data = data.chart_3_scatter_data;
      chart_3_line_data = data.chart_3_line_data;
      chart_4_params_x_axis = data.chart_1_params_x_axis;
      chart_4_spline_data = data.chart_4_spline_data;
      chart_4_scatter_data = data.chart_4_scatter_data;
      chart_4_line_data = data.chart_4_line_data;
      chart_5_params_x_axis = data.chart_1_params_x_axis;
      chart_5_spline_data = data.chart_5_spline_data;
      chart_5_scatter_data = data.chart_5_scatter_data;
      chart_5_line_data = data.chart_5_line_data;
      chart_6_params_x_axis = data.chart_1_params_x_axis;
      chart_6_spline_data = data.chart_6_spline_data;
      chart_6_scatter_data = data.chart_6_scatter_data;
      chart_6_line_data = data.chart_6_line_data;
      chart_7_params_x_axis = data.chart_1_params_x_axis;
      chart_7_spline_data = data.chart_7_spline_data;
      chart_7_scatter_data = data.chart_7_scatter_data;
      chart_7_line_data = data.chart_7_line_data;      

      chart_1_params = {
        chart: {
          backgroundColor: '#efefef',
          font: '"Ubuntu", Arial, Helvetica, sans-serif',
          type: 'scatter', 
          zoomType: 'xy'
        },
        title: {
          text: 'Fleet Average Speed'
        },
        xAxis: {
          categories: chart_1_params_x_axis,
          labels: {
            enabled: false
          },
          title: {
            enabled: true,
            text: 'Trucks'
          },
          showLastLabel: false
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Speed (km/h)'
          }
        },
        legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'top',
          x: 100,
          y: 70,
          floating: true,
          backgroundColor: '#EFEFEF',
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
          },
          series: {
            turboThreshold: 0
          }
        },
        series: [
         {
            name: 'Truck Trips',
            data: chart_1_scatter_data,
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} km/hr'
            }
          },
          {
            name: 'Average speeds',
            type: 'spline',
            data: chart_1_spline_data,
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.name} <br><b> Average speed: </b>{point.y} km/hr <br> <b>Std Dev:</b> {point.myData} '
            }    
          },     
          {
            type: 'line',
            name: 'Global Average Speed',
            data: chart_1_line_data,
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Average speed: </b>{point.y} km/hr <br> <b>Std Dev:</b> {point.myData} '
            },             
          } 
        ]
      }; //chart 1

      chart_2_params = {
        chart: {
          backgroundColor: '#efefef',
          font: '"Ubuntu", Arial, Helvetica, sans-serif',
          type: 'scatter', 
          zoomType: 'xy'
        },
        title: {
          text: 'Fleet STEM Distance'
        },
        xAxis: {
          categories: chart_2_params_x_axis,
          labels: {
            enabled: false
          },          
          title: {
            enabled: true,
            text: 'Trucks'
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'STEM Distance (km)'
          }
        },
        legend: {
          layout: 'vertical',
          align: 'left',
          verticalAlign: 'top',
          x: 100,
          y: 70,
          floating: true,
          backgroundColor: '#EFEFEF',
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
          },
          series: {
            turboThreshold: 0
          }
        },
        series: [
         {
            name: 'STEM per Trip',
            data: chart_2_scatter_data,
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} km'
            }
          },
          {
            name: 'STEM Distance',
            type: 'spline',
            data: chart_2_spline_data,
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.name} <br><b> STEM: </b>{point.y} km <br> <b>Std Dev:</b> {point.myData} '
            }    
          },     
          {
            type: 'line',
            name: 'Global STEM Distance',
            data: chart_2_line_data,
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Fleet STEM: </b>{point.y} km <br> <b>Std Dev:</b> {point.myData} '
            },             
          } 
        ]
      }; //chart 2

      chart_3_params = {
        chart: {
          backgroundColor: '#efefef',
          font: '"Ubuntu", Arial, Helvetica, sans-serif',
          type: 'scatter', 
          zoomType: 'xy'
        },
        title: {
          text: 'Fleet Avg. Distance'
        },
        xAxis: {
          categories: chart_3_params_x_axis,
          labels: {
            enabled: false
          },
          title: {
            text: 'Trucks',
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Trip Distance (km)'
          }
        },
        legend: {
          layout: 'vertical',
          align: 'left',
          verticalAlign: 'top',
          x: 100,
          y: 70,
          floating: true,
          backgroundColor: '#EFEFEF',
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
          },
          series: {
            turboThreshold: 0
          }
        },
        series: [
         {
            name: 'Truck 1',
            data: chart_3_scatter_data,
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} km'
            }
          },
          {
            name: 'Trip Distance',
            type: 'spline',
            data: chart_3_spline_data,
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.name} <br><b> Trip Distance: </b>{point.y} km <br> <b>Std Dev:</b> {point.myData} '
            }    
          },     
          {
            type: 'line',
            name: 'Global Trip Distance',
            data: chart_3_line_data,
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Trip Distance: </b>{point.y} km <br> <b>Std Dev:</b> {point.myData} '
            },             
          } 
        ]
      }; //chart 3 

      chart_4_params = {
        chart: {
          backgroundColor: '#efefef',
          font: '"Ubuntu", Arial, Helvetica, sans-serif',
          type: 'scatter', 
          zoomType: 'xy'
        },
        title: {
          text: 'Stops'
        },
        xAxis: {
          categories: chart_4_params_x_axis,
          labels: {
            enabled: false
          },
          title: {
            text: 'Trucks',
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Stops'
          }
        },
        legend: {
          layout: 'vertical',
          align: 'left',
          verticalAlign: 'top',
          x: 100,
          y: 70,
          floating: true,
          backgroundColor: '#EFEFEF',
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
          },
          series: {
            turboThreshold: 0
          }
        },
        series: [
          {
            name: 'Stops per Trip',
            data: chart_4_scatter_data,
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} '
            }
          },
          {
            name: 'Stops',
            type: 'spline',
            data: chart_4_spline_data,
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.name} <br><b> Stops: </b>{point.y} <br> <b>Std Dev:</b> {point.myData} '
            }    
          },     
          {
            type: 'line',
            name: 'Global Stops',
            data: chart_4_line_data,
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Stops: </b>{point.y} <br> <b>Std Dev:</b> {point.myData} '
            },             
          } 
        ]
      }; //chart 4  

      chart_5_params = {
        chart: {
          backgroundColor: '#efefef',
          font: '"Ubuntu", Arial, Helvetica, sans-serif',
          type: 'scatter', 
          zoomType: 'xy'
        },
        title: {
          text: 'Time Traveling'
        },
        xAxis: {
          categories: chart_5_params_x_axis,
          labels: {
            enabled: false
          },
          title: {
            text: 'Trucks',
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Time (hrs.)'
          }
        },
        legend: {
          layout: 'vertical',
          align: 'left',
          verticalAlign: 'top',
          x: 100,
          y: 70,
          floating: true,
          backgroundColor: '#EFEFEF',
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
          },
          series: {
            turboThreshold: 0
          }
        },
        series: [
          {
            name: 'Time Traveling per Trip',
            data: chart_5_scatter_data,
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} hrs.'
            }
          },
          {
            name: 'Time Traveling',
            type: 'spline',
            data: chart_5_spline_data,
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.name} <br><b> Time: </b>{point.y} hrs. <br> <b>Std Dev:</b> {point.myData} min.'
            }    
          },     
          {
            type: 'line',
            name: 'Global Time Traveling',
            data: chart_5_line_data,
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Time: </b>{point.y} hrs. <br> <b>Std Dev:</b> {point.myData} min.'
            },             
          } 
        ]
      }; //chart 5  
      
      chart_6_params = {
        chart: {
          backgroundColor: '#efefef',
          font: '"Ubuntu", Arial, Helvetica, sans-serif',
          type: 'scatter', 
          zoomType: 'xy'
        },
        title: {
          text: 'Time in Stops'
        },
        xAxis: {
          categories: chart_6_params_x_axis,
          labels: {
            enabled: false
          },
          title: {
            text: 'Trucks',
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Time (hrs.)'
          }
        },
        legend: {
          layout: 'vertical',
          align: 'left',
          verticalAlign: 'top',
          x: 100,
          y: 70,
          floating: true,
          backgroundColor: '#EFEFEF',
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
          },
          series: {
            turboThreshold: 0
          }
        },
        series: [
          {
            name: 'Time in Stops per Trip',
            data: chart_6_scatter_data,
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} hrs.'
            }
          },
          {
            name: 'Time in Stops',
            type: 'spline',
            data: chart_6_spline_data,
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.name} <br><b> Time: </b>{point.y} hrs. <br> <b>Std Dev:</b> {point.myData} min.'
            }    
          },     
          {
            type: 'line',
            name: 'Global Time in Stops',
            data: chart_6_line_data,
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Time: </b>{point.y} hrs. <br> <b>Std Dev:</b> {point.myData} min.'
            },             
          } 
        ]
      }; //chart 6   

      chart_7_params = {
        chart: {
          backgroundColor: '#efefef',
          font: '"Ubuntu", Arial, Helvetica, sans-serif',
          type: 'scatter', 
          zoomType: 'xy'
        },
        title: {
          text: 'Time in Trip'
        },
        xAxis: {
          categories: chart_7_params_x_axis,
          labels: {
            enabled: false
          },
          title: {
            text: 'Trucks',
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Time (hrs.)'
          }
        },
        legend: {
          layout: 'vertical',
          align: 'left',
          verticalAlign: 'top',
          x: 100,
          y: 70,
          floating: true,
          backgroundColor: '#EFEFEF',
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
          },
          series: {
            turboThreshold: 0
          }
        },
        series: [
          {
            name: 'Time in Trip',
            data: chart_7_scatter_data,
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} hrs.'
            }
          },
          {
            name: 'Time in Trip',
            type: 'spline',
            data: chart_7_spline_data,
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.name} <br><b> Time: </b>{point.y} hrs. <br> <b>Std Dev:</b> {point.myData} min.'
            }    
          },     
          {
            type: 'line',
            name: 'Global Time in Trip',
            data: chart_7_line_data,
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Time: </b>{point.y} hrs. <br> <b>Std Dev:</b> {point.myData} min.'
            },             
          } 
        ]
      }; //chart 7                  
      
      chart_slider_1 = ChartSlider('#charts-container','#stats-slider-left-arrow','#stats-slider-right-arrow')
      chart_slider_1.addElement(chart_1_params);
      chart_slider_1.addElement(chart_2_params);    
      chart_slider_1.addElement(chart_3_params);    
      chart_slider_1.addElement(chart_4_params);  
      chart_slider_1.addElement(chart_5_params);  
      chart_slider_1.addElement(chart_6_params);
      chart_slider_1.addElement(chart_7_params);                          
      chart_slider_1.start();
    } // if

  }, //function
  error: function(xhr, ajaxOptions, thrownError) {
    alert(xhr.statusText);
    alert(thrownError);
  }   

});

Highcharts.setOptions({
  colors: ['#3f51b4', '#e51c23', '#72d572', '#f06292', '#ba68c8', '#ffff00', '#ffaf4c', '#a1887f', '#9e9e9e', '#b0120a', '#e91e63', '#9c27b0', '#1a237e', '#259b24', '#ffe04c', '#ff9800', '#795548', '#424242', '#f36c60', '#880e4f', '#4a148c', '#91a7ff', '#0d4502', '#ffc107', '#e65100', '#3e2723', '#b0bec5', '#9575cd', '#5677fc', '#88ee7b', '#ff6f00', '#ffab91', '#607d8b'],
});

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
