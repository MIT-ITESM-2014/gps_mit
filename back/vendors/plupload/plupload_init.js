
var maxfiles = 1;

var step_number;

function removeFile(up, file_id)
{
  $('#'+file_id).remove();
  up.removeFile(file_id);
  if(up.files.length == maxfiles)
  {
    $('#start-upload').fadeIn("slow");
    $('#browse').hide("fast"); 
  }
  else if(up.files.length <= maxfiles)
  {
    $('#browse').fadeIn("slow");
    $('#start-upload').hide("fast");
  }
}

var uploader = new plupload.Uploader({
  browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
  url: 'index.php?r=sample/uploadOne',
  filters : {
  max_file_count: maxfiles,
    max_file_size : '30mb',
    mime_types: [
      {title : "CSV", extensions : "csv"}
    ]
  },
  init : {
    PostInit: function() {
      // Called after initialization is finished and internal event handlers bound
      $('#start-upload').hide("fast");
      $('.moxie-shim-html5').hide(); 
      
    },
    FilesAdded: function(up, files) {
      var last_file = up.files[up.files.length - 1];
      
      if(up.files.length <= maxfiles)
      {
        $('#'+last_file.id).append('<div id="delete'+last_file.id+'">X</div>');
        $('#delete'+last_file.id).click(function(){
          removeFile(up,last_file.id);
        });
        
        
      }
      
      if(up.files.length == maxfiles)
      {
        $('#start-upload').fadeIn("slow");
        $('#browse').hide("fast");
      }
      else if(up.files.length > maxfiles) //Must delete the last file
      {
        up.removeFile(last_file.id);
        $('#'+last_file.id).remove();
      }
      
    },
    FilesRemoved: function(up, files) {
      if (up.files.length < maxfiles) {
         $('#browse').fadeIn("slow");
       }
    },
    UploadComplete : function(up, files){
      
      var req = new XMLHttpRequest();

      req.onreadystatechange=function()
      {
        if (req.readyState==4 && req.status==200)
        {
          var index;
          for(index = 0; index < files.length; index++)
          {
            $('#'+files[index].id).remove();
            removeFile(up,files[index].id);
          }
          $('#browse').fadeIn("slow");
          $('#start-upload').hide("fast");
        }
      }

      req.open("GET", "index.php?r=sample/createPartial", true);
      req.send();
      if(step_number == 0)
      {
        up.settings.url = 'index.php?r=sample/uploadTwo'
        step_number = 1;
      }
      else if(step_number == 1)
      {
        up.settings.url = 'index.php?r=sample/uploadOne';
        step_number = 0;
      }
    }
  }
});
 
 
 //$( document ).ready(init_uploader());
 
 function init_uploader(){

uploader.init();
 
uploader.bind('FilesAdded', function(up, files) {
  var html = '';
  plupload.each(files, function(file) {
    html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
  });
  document.getElementById('filelist').innerHTML += html;
});
 
uploader.bind('UploadProgress', function(up, file) {
  document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
});
 
uploader.bind('Error', function(up, err) {
  document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
});
 
document.getElementById('start-upload').onclick = function() {
  uploader.start();
};
}
 
