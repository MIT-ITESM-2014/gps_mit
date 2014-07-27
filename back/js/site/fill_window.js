
var page_contents_div = $('#page-contents');
var mainmenu_container_div = $('#mainmenu-container');
var content_div = $('#content');
var body_div = $('body');
var all_content_container_div = $('#all_content_container');

$(window).bind("resize", function(){
    fillWindow();
});
//670
function fillWindow(){
    //var imageHeight = $(element).height();
    //var imageWidth = $(element).width();
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    
    if (windowWidth < 712)
    {
      body_div.css({"width":"712px"});
    }
    else
    { 
      body_div.css({"width":windowWidth+"px"});
    }
    var page_contents_height = windowHeight - 60;
    if(page_contents_height < 610)
    {
      page_contents_height = 610;
    }
    page_contents_div.css({
      "height": page_contents_height+"px"
    });
    
    var mainmenu_container_height = windowHeight - 60;
    if(mainmenu_container_height < 550)
    {
      mainmenu_container_height = 550;
    }
    mainmenu_container_div.css({
      "height": mainmenu_container_height+"px"
    });
    
    var content_width = windowWidth - 262;
    if(content_width < 450)
    {
      content_width = 450;
    }
    content_div.css({
      "width": content_width+"px",
      "height": mainmenu_container_height+"px"
    });
    var all_content_container_div_height = mainmenu_container_height - 70;
    if(all_content_container_div_height < 480)
    {
      all_content_container_div_height = 480;
    }
    all_content_container_div.css({
      "height" : all_content_container_div_height+"px"
    });
    
};

fillWindow();

