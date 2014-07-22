$('#icons-bar').hide();


$('<td id="prueba"></td>').prependTo('table > thead > tr.filters');
//$('.filters').append('<td id="prueba"></td>');

$('#prueba').addClass('hola');

$('.filters td:nth-child(2)').append('<input id="fleet-search-form">');