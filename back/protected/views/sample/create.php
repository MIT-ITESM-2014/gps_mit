<?php
/* @var $this SampleController */
/* @var $model Sample */

$this->breadcrumbs=array(
	'Samples'=>array('index'),
	'Create',
);
?>
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
	<h1> Upload CSV File</h1>
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
        <p>Format Requirements</p>
        <p>The file must have four columns: the first one must have the truck name, the second column must have </p>
        <p> the latitude, the third column must have the longitude, and the fourth column must contain the timestamp. </p>
      </div>
      <br />
      <div class="explanation-par-two">
        <p>The first row of the file must have the headers of the columns. You can use any name for each column, </p>
        <p>but remember to follow the right order (truck name, latitude, longitude, timestamp)</p>
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
        <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spinner.gif"></img></div>
        <div class="row-parameter-single">
                <div class="parameter-label"> <?php echo $parameters_form->labelEx($parameter_model,'distance_ratio_long_stop'); ?> </div>
                <div class="parameter-input"> <?php echo $parameters_form->textField($parameter_model,'distance_ratio_long_stop',array('size'=>60,'maxlength'=>500)); ?> </div>
                <div class="parameter-error"> <?php echo $parameters_form->error($parameter_model,'distance_ratio_long_stop'); ?> </div>
        </div>

        <div class="row-parameter-single">
                <div class="parameter-label"> <?php echo $parameters_form->labelEx($parameter_model,'time_ratio_long_stop'); ?> </div>
                <div class="parameter-input"> <?php echo $parameters_form->textField($parameter_model,'time_ratio_long_stop',array('size'=>60,'maxlength'=>500)); ?> </div>
                <div class="parameter-error"> <?php echo $parameters_form->error($parameter_model,'time_ratio_long_stop'); ?> </div>
        </div>
        <div class="buttons">
                
         <?php echo CHtml::ajaxSubmitButton('Continue',CHtml::normalizeUrl(array('sample/submitParameters','render'=>true)),
           array(
             'dataType'=>'json',
             'type'=>'post',
             'success'=>'function(data) {
                
                 //$("#AjaxLoader").hide();  
                if(data.status=="success"){
                  alert("voy a acutalizar a pso 0");
                  actualizarPaso(0);
                  //$("#formResult").html("form submitted successfully.");
                  //$("#user-form")[0].reset();
                }
                else{
                  $.each(data, function(key, val) {
                    $("#parameters-form #"+key+"_em_").text(val);                                                    
                    $("#parameters-form #"+key+"_em_").show();
                  });
                }       
            }',                    
           'beforeSend'=>'function(){                        
                 //$("#AjaxLoader").show();
                 alert("Voy a envair");
            }'
           ),array('id'=>'submitButton','class'=>'submit_button')); ?>
  </div>
<?php $this->endWidget();?>
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

