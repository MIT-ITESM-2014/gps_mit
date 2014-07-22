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
          var block_height = windowHeight - 0;
          if(block_height < 680)
          {
            block_height = 680;
          }       
          $(element).css({
              "position" : "absolute",
              "left" : 0,
              "top" : "-70px",
              "background-color": "#68d8c5",
              "width": windowWidth,
              "height": block_height+"px",
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
