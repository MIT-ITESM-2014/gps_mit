$('#routes-section').removeClass('routes-section');
$('#routes-section').addClass('active_routes');

$('#background-content').screen_block_init('Gris-Op90.png');
$('#background-content').screen_block_block();

$('#button_update_map').click(function(){
	$('#background-content').screen_block_unblock();

});