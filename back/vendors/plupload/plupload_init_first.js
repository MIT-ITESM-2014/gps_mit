function init_uploader_first(){
  step_number = 0;
  init_uploader();
  uploader.settings.url = 'index.php?r=sample/uploadOne';
}

$( document ).ready(init_uploader_first());


