

var content_container_div = $('#content-container');
$(window).bind("resize", function(){
    fillWindow();
});

function fillWindow(){       
    //var imageHeight = $(element).height();
    //var imageWidth = $(element).width();
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
//    var height = windowHeight-750;
//    if(height < 0)
//    {
 //     height = 0;
 //   }
    
 //   right_div.css({
 //     "width": width+"px"
 //   });
    var content_container_height = windowHeight-70;
    if(content_container_height < 680)
    {
      content_container_height = 680;
    }
    content_container_div.css({
      "height": content_container_height+"px"
    });
};

fillWindow();

