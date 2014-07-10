(function($){
 
  $.fn.center = function(){
    alert("lololo");
    var element = this;
    
    //$(element).load(function(){
       
      changeCss();
       
      $(window).bind("resize", function(){
          alert("cambie de tamaó");
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

alert("voy a cnetrar:");

$(function(){
  $("#prueba").center();
  
});
