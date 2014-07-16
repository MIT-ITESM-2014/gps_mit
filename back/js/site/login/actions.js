(function($){
  $.fn.fillWindow = function(){
    var element = this;
      $(window).bind("resize", function(){
          changeCss();
      });
       
      function changeCss(){
       
          //var imageHeight = $(element).height();
          //var imageWidth = $(element).width();
          var windowWidth = $(window).width();
          var windowHeight = $(window).height();
       
          $(element).css({
              "position" : "absolute",
              "left" : 0,
              "top" : 0,
              "background-color": "#68d8c5",
              "width": windowWidth,
              "height": windowHeight - 0,
          });
      };
      
      changeCss();
  };
 
})(jQuery);

$(function(){
  $("#login-container").fillWindow();
});

/*Login button*/

$("#login_simulated_button").click(function() {
  $("#login-submit-button-container input").click();
});
