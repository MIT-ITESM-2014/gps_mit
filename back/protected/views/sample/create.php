
<script type="text/javascript">
  function actualizarPaso(p1) {
      switch(p1){
        case 0:
          $("#file_upload_container").css("display", "block");
          $("#parameters_form_container").css("display", "none");
          break;
        case 1:
          $("#file_upload_container").css("display", "none");
          $("#parameters_form_container").css("display", "block");
          break;
      }
  }
</script>

<div class="headers">
	<h1> Upload CSV File for <?php echo Yii::app()->user->getState('current_company_name');?> </h1>
</div>
<div id="all_content_container">
<div id="screen_loading">
  <img id="spinner_image" src="<?php echo Yii::app()->request->baseUrl.'/public/images/spinner.gif';?>"/>
</div>

  <div id="file_upload_container" style="display:none">
    <div class="upload-guidelines">
      <div class="upload-guidelines-file">
        <div class="upload-guidelines-file-image"></div>
      </div>
      <div class="upload-guidelines-arrow"></div>
      <div class="upload-guidelines-params">
        <div class="upload-guidelines-params-image"></div>
      </div>
    </div>
    <div class="upload-file-explanation">
      <p> Please provide a .csv file containing the GPS information of the trucks.</p>
      <br />
      <div class="explanation-par-one">
        <p>The file must have four columns: truck name, latitude, longitude, and timestamp. </p>
      </div>
      <br />
      <div class="explanation-par-two">
        <p>The first row of the file must have the headers of the columns(truck name, latitude, longitude, timestamp).</p>
      </div>
      <br />
      <p>You can download a sample file with the required format.</p>
    </div>
    <div class="download-sample-file-button"> </div>
    <div class="files-to-upload">
      <ul id="filelist"></ul>
    </div>
    <div id="container">
      <a id="browse" href="javascript:;"> <div class="browse-files-button"> </div></a>
      <div id="progress-bar-number"> 0% </div>
      <br/>
      <a id="start-upload" href="javascript:;"><div class="start-upload-button"></div></a>
    </div>
    <br />
    <pre id="console"></pre>
  </div>
  
  
  <div id="parameters_form_container" style="display:none">
<?php

    $parameters_form=$this->beginWidget('CActiveForm', array(
      'id'=>'parameters-form',
      //'enableAjaxValidation'=>true,
      'action'=>$this->createUrl('sample/sendParameters'),
      'enableClientValidation'=>true,
    ));
?>
  <div class="upload-guidelines-two">
    <div class="upload-guidelines-file-two">
      <div class="upload-guidelines-file-image-two"></div>
    </div>
    <div class="upload-guidelines-arrow-two"></div>
    <div class="upload-guidelines-params-two">
      <div class="upload-guidelines-params-image-two"></div>
    </div>
  </div>
  <div class="parameters-form">
    <p> Please provide the parameters that best fit your organization's data. </p>
  
  <div class="errorMessage" id="formResult"></div>
        <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/public/images/spinner.gif"></img></div>
        <div class="row-parameter-single">
                <div class="parameter-label"> <?php echo $parameters_form->labelEx($parameter_model,'distance_radius_long_stop'); ?> </div>
                <div class="parameter-input"> <?php echo $parameters_form->textField($parameter_model,'distance_radius_long_stop',array('size'=>60,'maxlength'=>500)); ?> km</div>
                <div class="parameter-error"> <?php echo $parameters_form->error($parameter_model,'distance_radius_long_stop'); ?> </div>
        </div>

        <div class="row-parameter-single">
                <div class="parameter-label"> <?php echo $parameters_form->labelEx($parameter_model,'time_radius_long_stop'); ?> </div>
                <div class="parameter-input"> <?php echo $parameters_form->textField($parameter_model,'time_radius_long_stop',array('size'=>60,'maxlength'=>500)); ?> s</div>
                <div class="parameter-error"> <?php echo $parameters_form->error($parameter_model,'time_radius_long_stop'); ?> </div>
        </div>
        <div class="row-parameter-single">
                <div class="parameter-label"> <?php echo $parameters_form->labelEx($parameter_model,'distance_radius_short_stop'); ?> </div>
                <div class="parameter-input"> <?php echo $parameters_form->textField($parameter_model,'distance_radius_short_stop',array('size'=>60,'maxlength'=>500)); ?> km</div>
                <div class="parameter-error"> <?php echo $parameters_form->error($parameter_model,'distance_radius_short_stop'); ?> </div>
        </div>

        <div class="row-parameter-single">
                <div class="parameter-label"> <?php echo $parameters_form->labelEx($parameter_model,'time_radius_short_stop'); ?> </div>
                <div class="parameter-input"> <?php echo $parameters_form->textField($parameter_model,'time_radius_short_stop',array('size'=>60,'maxlength'=>500)); ?> s</div>
                <div id="information-icon" class="information-icon"> </div>
                <div id="parameter-information-box" class="parameter-information-box"> </div>
                <div class="parameter-error"> <?php echo $parameters_form->error($parameter_model,'time_radius_short_stop'); ?> </div>
        </div>
      
        <div class="buttons">
         <div id="upload-continue-button" class="upload-continue-button"></div>       
         <?php echo CHtml::ajaxSubmitButton('Continue',CHtml::normalizeUrl(array('sample/submitParameters','render'=>true)),
             array(
               'dataType'=>'json',
               'type'=>'post',
               'success'=>'function(data) {
                  hide_screen_loading();
                  if(data.status=="success"){
                    display_file_in_process();
                  }
                  else{
                    $.each(data, function(key, val) {
                      $("#parameters-form #"+key+"_em_").text(val);                                                    
                      $("#parameters-form #"+key+"_em_").show();
                    });
                    
                    show_parameters_form_container();
                  }       
                }',                    
               'error'=>'function(){
                 hide_screen_loading();
                 alert("We are sorry. There was a problem sending your data.");
               }',
               'beforeSend'=>'function(){                        
                   show_screen_loading();
                }'
             ),
             array('id'=>'submitButton','class'=>'submit_button')
           );
         ?>
        </div>  
<?php $this->endWidget();?>
  </div>
</div>  
<div id="file_in_process_container" class="reset-form-container">
	The file is being processed at the moment. You must wait until all the statistics are generated before viewing the fleet information or uploading another file.
</div>
</div>
<div id="ajax_content" name="ajax_content" >

  <?php
    $data = array(
      'step'=>$step,
      'parameter_model'=>$parameter_model,
      'script'=>$script,
    );
    $this->renderPartial('_ajaxContent', $data);
  ?>
</div>

<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/sample/actions.js',CClientScript::POS_END);
?>

