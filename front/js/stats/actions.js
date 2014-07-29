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
var chart_4_params; //duration chart
var chart_5_params; //
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

      chart_1_params_categories = data.charts_params_x_axis;
      chart_1_spline_data = data.chart_1_spline_data;
      chart_1_scatter_data = data.chart_1_scatter_data;
      chart_2_spline_data = data.chart_2_spline_data;
      chart_2_scatter_data = data.chart_2_scatter_data;
      chart_4_spline_data = data.chart_4_spline_data;

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
            data: [[3,22.666],[4,22.666]],
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Average speed: </b>{point.y} km/hr <br> <b>Std Dev:</b> 5246.25 '
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
            data: [[3,22.666],[4,22.666]],
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Fleet STEM: </b>{point.y} km <br> <b>Std Dev:</b> 5246.25 '
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
          text: 'Fleet Trip Distance'
        },
        xAxis: {
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
          }
        },
        series: [
         {
            name: 'Truck 1',
            data: [[1,29.6168772903697],    [1,21.710420500137],    [1,18.4374018910591],   [1,20.6588635402431],   [1,12.2755187992684],   [1,18.6425413802136],   [1,21.6917199879317],   [1,21.4966229264807],   [1,18.6389207946972],   [1,13.5235911230081],   [1,16.4549850860935],   [1,19.6370650170991],   [1,16.4123738548534],   [1,20.7996864860149],   [1,17.0268825213999],   [1,14.4401406736372],   [1,15.2451085238117],   [1,14.4355578753814],   [1,18.9528583635302],   [1,18.9106368387885],   [1,19.7465168531254],   [1,17.3292023760377],   [1,25.1598483238365],   [1,18.3350731606583],   [1,22.4600285922383],   [1,16.7769253765431],   [1,18.5605342791375],   [1,24.5168761729401],   [1,17.6055630894609],   [1,19.9904189915643],   [1,13.0341123446405],   [1,15.2547626364192],   [1,21.5474964696251],   [1,14.3803969507035],   [1,19.234362175853],    [1,8.3911740171655],    [1,21.9919662728887],   [1,21.0003654690072],   [1,32.9154088850214],   [1,27.9510648861605],   [1,17.7274908345838],   [1,16.6890761656534],   [1,18.9450660799983],   [1,20.7161685036568],   [1,10.4595859548377],   [1,14.1476647019417],   [1,15.7568782892531],   [1,17.2265600052932],   [1,13.59064715076]],
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} km'
            }
          },
          {
            name: 'Truck 3',
            data: [[3,36.4192662578322],    [3,23.3954864394315],   [3,28.2443512139095],   [3,31.3342819542932],   [3,25.3808098111335],   [3,33.184508935182],    [3,29.9937275093661],   [3,25.622212838066],    [3,25.4064790226299],   [3,26.0003268016878],   [3,55.5075845462075],   [3,24.6164999406248],   [3,37.623553107224],    [3,25.6159800759091],   [3,16.1954113531788],   [3,41.2218901539095],   [3,23.6325354930491],   [3,17.3838886930885],   [3,25.4400445430204],   [3,32.0532493588614],   [3,22.3247016546927],   [3,39.8456725612382],   [3,16.2579138780569],   [3,26.0094818940368],   [3,24.973587494579],    [3,30.0653064445404],   [3,41.2622200681754],   [3,26.5485583636993],   [3,25.3476815485334],   [3,23.6501589916928],   [3,30.6417892727997],   [3,25.6738072454191],   [3,26.8864498609616],   [3,32.0819292465994],   [3,32.8149851794678],   [3,30.3960535481312],   [3,39.3963517965265],   [3,34.3438177383107],   [3,19.9035240608764],   [3,21.4789314631686],   [3,22.5533046128398],   [3,29.3935096861624],   [3,25.3300189149628],   [3,22.0246486465138],   [3,29.0187532193488],   [3,26.2935570203531],   [3,24.1115349553915],   [3,28.9367603613796],   [3,24.4046442237254]],
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} km'
            }
          },
          {
            name: 'Truck 4',
            data: [[4,21.8793090113229],    [4,49.5193511515917],   [4,11.9132676259303],   [4,22.9991556641517],   [4,16.9292725597706],   [4,28.9056985318881],   [4,24.181057787082],    [4,12.1939794409517],   [4,18.8016913171313],   [4,13.2437699106956],   [4,26.243213926821],    [4,13.0350490253065],   [4,16.9487270403364],   [4,20.2049001691256],   [4,21.9489849719111],   [4,30.7479457552038],   [4,15.6824995635227],   [4,16.8316425311653],   [4,20.9483764665183],   [4,16.0415655721907],   [4,23.3612660994862],   [4,38.2037750064563],   [4,24.1813005200534],   [4,17.9427354511505],   [4,2.09034874328437],   [4,27.1234309201333],   [4,16.3425221637047],   [4,19.4764786470265],   [4,15.0591955799501],   [4,19.561918562772],    [4,22.5890753036302],   [4,21.0335966411919],   [4,22.7784384494913],   [4,3.10965494572843],   [4,23.6768751852944],   [4,3.58204198982264],   [4,17.2017782206401],   [4,20.4101628001036],   [4,17.5230320152644],   [4,29.0278266162057],   [4,2.7144245034085],    [4,4.30590760034423],   [4,32.6926077017551],   [4,26.5580837234296],   [4,12.7972789894442],   [4,10.8548665957221],   [4,4.44885519384639],   [4,28.7736365847795],   [4,21.1512038806097]],
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} km'
            }
          },
          {
            name: 'Trip Distance',
            type: 'spline',
            data: [{x:1,y:18.39734,myData:4.336997708},{x:3,y:21.327331,myData:9.396250082},{x:4,y:28.2906478,myData:7.092719413}],
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.x} <br><b> Trip Distance: </b>{point.y} km <br> <b>Std Dev:</b> {point.myData} '
            }    
          },     
          {
            type: 'line',
            name: 'Global Trip Distance',
            data: [[1,22.666],[4,22.666]],
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Trip Distance: </b>{point.y} km <br> <b>Std Dev:</b> 5246.25 '
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
          text: 'Fleet Trip Duration'
        },
        xAxis: {
          title: {
            text: 'Trucks',
            categories: chart_1_params_categories
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Duration (hrs)'
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
          }
        },
        series: [
         {
            name: 'Truck 1',
            data: [[1,29.6168772903697],    [1,21.710420500137],    [1,18.4374018910591],   [1,20.6588635402431],   [1,12.2755187992684],   [1,18.6425413802136],   [1,21.6917199879317],   [1,21.4966229264807],   [1,18.6389207946972],   [1,13.5235911230081],   [1,16.4549850860935],   [1,19.6370650170991],   [1,16.4123738548534],   [1,20.7996864860149],   [1,17.0268825213999],   [1,14.4401406736372],   [1,15.2451085238117],   [1,14.4355578753814],   [1,18.9528583635302],   [1,18.9106368387885],   [1,19.7465168531254],   [1,17.3292023760377],   [1,25.1598483238365],   [1,18.3350731606583],   [1,22.4600285922383],   [1,16.7769253765431],   [1,18.5605342791375],   [1,24.5168761729401],   [1,17.6055630894609],   [1,19.9904189915643],   [1,13.0341123446405],   [1,15.2547626364192],   [1,21.5474964696251],   [1,14.3803969507035],   [1,19.234362175853],    [1,8.3911740171655],    [1,21.9919662728887],   [1,21.0003654690072],   [1,32.9154088850214],   [1,27.9510648861605],   [1,17.7274908345838],   [1,16.6890761656534],   [1,18.9450660799983],   [1,20.7161685036568],   [1,10.4595859548377],   [1,14.1476647019417],   [1,15.7568782892531],   [1,17.2265600052932],   [1,13.59064715076]],
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} hrs'
            }
          },
          {
            name: 'Truck 3',
            data: [[3,36.4192662578322],    [3,23.3954864394315],   [3,28.2443512139095],   [3,31.3342819542932],   [3,25.3808098111335],   [3,33.184508935182],    [3,29.9937275093661],   [3,25.622212838066],    [3,25.4064790226299],   [3,26.0003268016878],   [3,55.5075845462075],   [3,24.6164999406248],   [3,37.623553107224],    [3,25.6159800759091],   [3,16.1954113531788],   [3,41.2218901539095],   [3,23.6325354930491],   [3,17.3838886930885],   [3,25.4400445430204],   [3,32.0532493588614],   [3,22.3247016546927],   [3,39.8456725612382],   [3,16.2579138780569],   [3,26.0094818940368],   [3,24.973587494579],    [3,30.0653064445404],   [3,41.2622200681754],   [3,26.5485583636993],   [3,25.3476815485334],   [3,23.6501589916928],   [3,30.6417892727997],   [3,25.6738072454191],   [3,26.8864498609616],   [3,32.0819292465994],   [3,32.8149851794678],   [3,30.3960535481312],   [3,39.3963517965265],   [3,34.3438177383107],   [3,19.9035240608764],   [3,21.4789314631686],   [3,22.5533046128398],   [3,29.3935096861624],   [3,25.3300189149628],   [3,22.0246486465138],   [3,29.0187532193488],   [3,26.2935570203531],   [3,24.1115349553915],   [3,28.9367603613796],   [3,24.4046442237254]],
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} hrs'
            }
          },
          {
            name: 'Truck 4',
            data: [[4,21.8793090113229],    [4,49.5193511515917],   [4,11.9132676259303],   [4,22.9991556641517],   [4,16.9292725597706],   [4,28.9056985318881],   [4,24.181057787082],    [4,12.1939794409517],   [4,18.8016913171313],   [4,13.2437699106956],   [4,26.243213926821],    [4,13.0350490253065],   [4,16.9487270403364],   [4,20.2049001691256],   [4,21.9489849719111],   [4,30.7479457552038],   [4,15.6824995635227],   [4,16.8316425311653],   [4,20.9483764665183],   [4,16.0415655721907],   [4,23.3612660994862],   [4,38.2037750064563],   [4,24.1813005200534],   [4,17.9427354511505],   [4,2.09034874328437],   [4,27.1234309201333],   [4,16.3425221637047],   [4,19.4764786470265],   [4,15.0591955799501],   [4,19.561918562772],    [4,22.5890753036302],   [4,21.0335966411919],   [4,22.7784384494913],   [4,3.10965494572843],   [4,23.6768751852944],   [4,3.58204198982264],   [4,17.2017782206401],   [4,20.4101628001036],   [4,17.5230320152644],   [4,29.0278266162057],   [4,2.7144245034085],    [4,4.30590760034423],   [4,32.6926077017551],   [4,26.5580837234296],   [4,12.7972789894442],   [4,10.8548665957221],   [4,4.44885519384639],   [4,28.7736365847795],   [4,21.1512038806097]],
            tooltip: {
              headerFormat: '<b>{point.key}</b><br>',
              pointFormat: '{point.y} hrs'
            }
          },
          {
            name: 'Duration',
            type: 'spline',
            data: [{x:1,y:18.39734,myData:4.336997708},{x:3,y:21.327331,myData:9.396250082},{x:4,y:28.2906478,myData:7.092719413}],
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Truck:</b>  {point.x} <br><b> Duration: </b>{point.y} hrs <br> <b>Std Dev:</b> {point.myData} '
            }    
          },     
          {
            type: 'line',
            name: 'Global Duration',
            data: [[1,22.666],[4,22.666]],
            marker: {
              enabled: false
            },
            tooltip: {
              headerFormat: '<b> </b>',
              pointFormat: '<b>Duration: </b>{point.y} hrs <br> <b>Std Dev:</b> 5246.25 '
            },             
          } 
        ]
      }; //chart 4             
      
      chart_slider_1 = ChartSlider('#charts-container','#stats-slider-left-arrow','#stats-slider-right-arrow')
      chart_slider_1.addElement(chart_1_params);
      chart_slider_1.addElement(chart_2_params);    
      chart_slider_1.addElement(chart_3_params);    
      chart_slider_1.addElement(chart_4_params);                              
      chart_slider_1.start();
    } // if

  }, //function
  error: function(xhr, ajaxOptions, thrownError) {
    alert(xhr.statusText);
    alert(thrownError);
  }   

});

Highcharts.setOptions({
  colors: ['#e51c23', '#3f51b4', '#72d572', '#f06292', '#ba68c8', '#ffff00', '#ffaf4c', '#a1887f', '#9e9e9e', '#b0120a', '#e91e63', '#9c27b0', '#1a237e', '#259b24', '#ffe04c', '#ff9800', '#795548', '#424242', '#f36c60', '#880e4f', '#4a148c', '#91a7ff', '#0d4502', '#ffc107', '#e65100', '#3e2723', '#b0bec5', '#9575cd', '#5677fc', '#88ee7b', '#ff6f00', '#ffab91', '#607d8b'],
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