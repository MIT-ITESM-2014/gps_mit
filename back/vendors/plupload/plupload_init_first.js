function init_uploader_first(){
  init_uploader();
  uploader.settings.url = 'index.php?r=sample/uploadOne';
}

$( document ).ready(init_uploader_first());


