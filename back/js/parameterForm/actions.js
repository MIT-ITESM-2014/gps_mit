
$('#screen_loading').hide();
$('#file_in_process_container').hide();
$('#parameter-information-box').hide();

function display_screen_loading()
{
  $('#screen_loading').fadeIn("fast");
  $('#file_in_process_container').hide();
  $('#parameters-form').hide();
}

function display_form()
{
  $('#screen_loading').hide();
  $('#file_in_process_container').hide();
  $('#parameters-form').fadeIn("fast");
}


function display_file_in_process()
{
  $('#screen_loading').hide();
  $('#file_in_process_container').fadeIn("fast");
  $('#parameters-form').hide();
}


$('#information-icon').mouseenter(function(){
	$('#parameter-information-box').show();
});

$('#information-icon').mouseleave(function(){
	$('#parameter-information-box').hide();
});

$('#upload-continue-button').click(function(){
	$('#submitButton').click();
});

