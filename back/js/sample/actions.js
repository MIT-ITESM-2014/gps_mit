
function show_screen_loading(){
  $("#file_upload_container").hide();
  $("#parameters_form_container").hide();
  $('#file_in_process_container').hide();
  $('#screen_loading').fadeIn("fast");
}

function hide_screen_loading(){
  $('#screen_loading').hide();
} 

function display_file_in_process()
{
  $('#file_in_process_container').fadeIn("slow");  
  $("#file_upload_container").hide();
  $("#parameters_form_container").hide();
  $('#screen_loading').hide();
}

hide_screen_loading();
$('#parameter-information-box').hide();
$('#file_in_process_container').hide();

//hiding submit button
$('#submitButton').hide();

$('#information-icon').mouseenter(function(){

	$('#parameter-information-box').show();

});

$('#information-icon').mouseleave(function(){

	$('#parameter-information-box').hide();

});

$('#upload-continue-button').click(function(){
	$('#submitButton').click();
});

