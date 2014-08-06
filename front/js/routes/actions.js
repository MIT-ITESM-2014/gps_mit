//Display as active the Trip section of the navigation bar
$('#routes-section').removeClass('routes-section').addClass('active_routes');


//Replacing polyline//TODO
function button_update_map_action()
{
  update_stats();
  update_map();
}

/*
 * Gets the selected route id
 * Gets the corresponding route samples with ajax
 * Displays the samples as thew new route
 */
function update_map()
{
  var route_id = document.getElementById("select-route").value;
  
  $.ajax({ 
    type: "GET",
    dataType: "json",
    url: "index.php?r=route/getRouteSamples&route_id="+route_id,
    success: function(data){
      console.log(data);
      var routeCoordinates = [];
      if(typeof data.coordinate_list[0] != 'undefined')
        map.setCenter(new google.maps.LatLng(data.coordinate_list[0]['lat'], data.coordinate_list[0]['long']));
      for(var i in data.coordinate_list)
      {
        routeCoordinates.push(new google.maps.LatLng(data['coordinate_list'][i]['lat'], data['coordinate_list'][i]['long']));
        
      }
      route.setMap(null);
      route = new google.maps.Polyline({
        path: routeCoordinates,
        geodesic: true,
        strokeColor: '#49CEAE',
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
      route.setMap(map);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.statusText);
      alert(thrownError);
    }   
  });
}
      
//Set onclick handler for "Go" button
/*document.getElementById("button_update_map").onclick = function() {
  button_update_map_action(); 
};*/


//Update stats of map legend
function update_stats()
{
  $.ajax({ 
      type: "GET",
      dataType: "json",
      url: "index.php?r=route/getRouteStats&route_id="+document.getElementById("select-route").value,
      success: function(data){
        var parsed_data = $.parseJSON(data);
        if(parsed_data != null)
        {
          $('#route-information-truck-name').empty();
          $('#route-information-truck-name').append(parsed_data['general_information']['truck_id']);
          $('#route-information-route-id').empty();
          $('#route-information-route-id').append(parsed_data['general_information']['route_id']);
          $('#route-information-date').empty();
          $('#route-information-date').append(parsed_data['general_information']['date']);
          $('#distance_data_container').empty();
          $('#distance_data_container').append(parsed_data['route_stats']['distance']);
          $('#time_data_container').empty();
          $('#time_data_container').append(parsed_data['route_stats']['duration']);
          $('#average_speed_data_container').empty();
          $('#average_speed_data_container').append(parsed_data['route_stats']['average_speed']);
          $('#short_stops_count_data_container').empty();
          $('#short_stops_count_data_container').append(parsed_data['route_stats']['short_stops_count']);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.statusText);
        alert(thrownError);
      }   
  });
}


//TODO
$('#select-route').prepend('<option>Choose a trip</option>');

/*
 * This function gets and sets the available dates for the selected truck
 */
function updateAvailableDate()
{
  $.ajax({
    type: 'GET',
    dataType: 'JSON',
    url: 'index.php?r=route/getAvailableDates&truck_id=' + document.getElementById('truck_selector').value, 
    success: function(data)
    {
      if(data != "")
      {
        var inactive_days = new Array();
        var min_date = data.min_date;
        var max_date = data.max_date;
        inactive_days = data.inactive_days;
        var choose_date_dp = $('#choose_date_dp');
        //var selectedDate = $('#choose_date_dp').val();
        choose_date_dp.datepicker('option', 'maxDate', max_date);
        choose_date_dp.datepicker('option', 'minDate', min_date);
        /*
         * This functions is used by the datepicker to verify for each day between
         * min_date and max_date if it should be enabled or not.
         */
         
        function disableDates(date)
        {
          var disabled_dates = inactive_days;
          for (var i = 0; i < inactive_days.length; i++) {
            if (new Date(inactive_days[i]).toString() == date.toString())
            {
              return [false, '', ''];
            }
          }
          return [true, '', ''];
        }
        choose_date_dp.datepicker('option', 'beforeShowDay', disableDates);
        //if(!selectedDate)
        var selectedDate = choose_date_dp.datepicker('option', 'all').minDate;
        choose_date_dp.datepicker('setDate', selectedDate);
        updateRouteList();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.statusText);
      alert(thrownError);
    }   
  });
}

/*
 * This function gets and sets the routes that are available for the
 * truck and date selected.
 */
function updateRouteList()
{
  var selected_truck = document.getElementById('truck_selector').value;
  var selected_date = document.getElementById('choose_date_dp').value;
  if((selected_truck != null) && (selected_date != null))
  {
    $.ajax(
      { 
        type: 'GET',
        dataType: 'JSON',
        url: 'index.php?r=route/getRouteList&truck_id='+selected_truck+'&start_date='+selected_date,
        success: function(data){        
          $('#select-route').find('option').remove();
          var parsed_data = $.parseJSON(data);
          $('#select-route').append('<option>Choose a trip</option>');
          $.each(parsed_data['routes'], function( index, value ) {
            $('#select-route').append('<option value="'+value['value']+'">'+value['name']+'</option>');
          });
          var first_route = $('#select-route option:eq(1)').val();
          $('#select-route').val(first_route);
          button_update_map_action();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.statusText);
          alert(thrownError);
        }   
      }
    );
  }
}



//Truck options TODO
$('#truck_selector').prepend('<option>Chooose a truck</option>');
$.ajax({
  type: 'GET',
  dataType: 'JSON',
  url: 'index.php?r=route/getTruckList', 
  success: function(data)
  {
    var truck_list = data.truck_list;
    for(var key in truck_list)
    {
      var newOption;
      newOption = $('<option value="'+key+'">'+truck_list[key]+'</option>'); 
      $('#truck_selector').append(newOption);  
    }
    var opt = $('#truck_selector option:eq(1)').val();
    $('#truck_selector').val(opt);
    $('#truck_selector').change(updateAvailableDate);
    $('#select-route').change(button_update_map_action);//working
    updateAvailableDate();
  },
  error: function (xhr, ajaxOptions, thrownError) {
    alert(xhr.statusText);
    alert(thrownError);
  }   
});

/*document.getElementById("truck_selector").onclick = function() {
  updateAvailableDate(); 
};*/

$('#choose_date_dp').change(updateRouteList);

