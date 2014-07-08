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
	<h1></h1>
</div>

  <div id="file_upload_container" style="display:none">
    <ul id="filelist"></ul>
    <br />
    <div id="container">
      <a id="browse" href="javascript:;">[Browse...]</a>
      <a id="start-upload" href="javascript:;">[Start Upload]</a>
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
  
  Please, choose the parameters that fit the best your organization's data.
  
  <div class="errorMessage" id="formResult"></div>
        <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/spinner.gif"></img></div>
        <div class="row-parameter-single">
                <?php echo $parameters_form->labelEx($parameter_model,'distance_ratio_long_stop'); ?>
                <?php echo $parameters_form->textField($parameter_model,'distance_ratio_long_stop',array('size'=>60,'maxlength'=>500)); ?>
                <?php echo $parameters_form->error($parameter_model,'distance_ratio_long_stop'); ?>
        </div>

        <div class="row-parameter-single">
                <?php echo $parameters_form->labelEx($parameter_model,'time_ratio_long_stop'); ?>
                <?php echo $parameters_form->textField($parameter_model,'time_ratio_long_stop',array('size'=>60,'maxlength'=>500)); ?>
                <?php echo $parameters_form->error($parameter_model,'time_ratio_long_stop'); ?>
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

