function init_uploader_second(){
  step_number = 1;
  init_uploader();
  uploader.settings.url = 'index.php?r=sample/uploadTwo';
}

$( document ).ready(init_uploader_second());
