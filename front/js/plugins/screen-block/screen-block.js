(function($){
 
  $.fn.screen_block_block = function(){
    var element = this;
    $(element).css({
        "display": "block",
    });
  };
  
  $.fn.screen_block_init = function(bcolor, opacity, alphaopacity){
    var startWindowWidth = $(this).parent().width();
    var startWindowHeight = $(this).parent().height();
    var element = this;
    $(element).css({
        "overflow" : "hidden",
        "left" : 0,
        "top" : 0,
        "background-color": bcolor,//"#003C4B",
        "opacity": opacity,//"0.9",
        "filter": "alpha(opacity="+alphaopacity+")",//90
        "width": startWindowWidth,
        "height": startWindowHeight - 100,
        "display": "none",
    });
    
    changeSize();
    $(window).bind("resize", function(){
        changeCss();
    });
     
    function changeSize(){
        var windowWidth = $(this).parent().width();
        if(windowWidth == null)
        {
          windowWidth = "100%";
        }
        var windowHeight = $(this).parent().height();
        if(windowHeight == null)
        {
          windowHeight = "100%";
        }
     
        $(element).css({
            "width": windowWidth,
            "height": windowHeight,
        });
    };
  };
  
  $.fn.screen_block_unblock = function(parameters){
    var element = this;
    $(element).css({
        "display": "none",
    });
  };
 
})(jQuery);
