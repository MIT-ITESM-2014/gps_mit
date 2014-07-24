$('#parameter-information-box').hide();

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

