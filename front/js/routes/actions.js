$('#routes-section').removeClass('routes-section').addClass('active_routes');

$('#truck_selector').prepend('<option>Choose a truck</option>');

$('#select-route').prepend('<option>Choose a trip</option>');

$('#truck_selector').change(updateAvailableDate);


function updateAvailableDate()
{
	$.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: 'index.php?r=route/getAvailableDates&truck_id=' + document.getElementById('truck_selector').value, 
		success: function(data)
		{
			var min_date = data.min_date;
			var max_date = data.max_date;
			var inactive_days = data.inactive_days;

			var choose_date_dp = $('#choose_date_dp');

			choose_date_dp.datepicker("option", "maxDate", max_date);
			choose_date_dp.datepicker("option", "minDate", min_date);
			
			var disabledDates = [inactive_days];
			console.log(disabledDates);
			//console.log(inactive_days);
			choose_date_dp.datepicker("option", "beforeShowDay", prueba);

			choose_date_dp.datepicker({beforeShowDay: function(d){
				alert("Before Show!!");
    		var disabledDates = [inactive_days];
        for (var i = 0; i < disabledDates.length; i++) {
          if (new Date(disabledDates[i]).toString() == d.toString()) {
            return [false,''];
          }
        }
			}});

		},
		error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.statusText);
      alert(thrownError);
    }   

	});
}