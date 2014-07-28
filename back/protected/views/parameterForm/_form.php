
<div id="parameters-form" class="parameters-form">
  <?php $form=$this->beginWidget('CActiveForm', array(
	  'id'=>'parameter-form-form',
	  'enableAjaxValidation'=>true,
  )); ?>
  <p> Please provide the parameters that best fit your organization's data. </p>
  <div class="errorMessage" id="formResult"></div>
  <div class="row-parameter-single">
    <div class="parameter-label"> <?php echo $form->labelEx($model,'distance_radius_long_stop'); ?> </div>
    <div class="parameter-input"> <?php echo $form->textField($model,'distance_radius_long_stop',array('size'=>60,'maxlength'=>500)); ?> km</div>
    <div class="clear"></div>
    <div class="parameter-error"> <?php echo $form->error($model,'distance_radius_long_stop'); ?> </div>
  </div>
  <div class="clear"></div>
  <div class="row-parameter-single">
    <div class="parameter-label"> <?php echo $form->labelEx($model,'time_radius_long_stop'); ?> </div>
    <div class="parameter-input"> <?php echo $form->textField($model,'time_radius_long_stop',array('size'=>60,'maxlength'=>500)); ?> s</div>
    <div class="clear"></div>
    <div class="parameter-error"> <?php echo $form->error($model,'time_radius_long_stop'); ?> </div>
  </div>
  <div class="clear"></div>
  <div class="row-parameter-single">
    <div class="parameter-label"> <?php echo $form->labelEx($model,'distance_radius_short_stop'); ?> </div>
    <div class="parameter-input"> <?php echo $form->textField($model,'distance_radius_short_stop',array('size'=>60,'maxlength'=>500)); ?> km</div>
    <div class="clear"></div>
    <div class="parameter-error"> <?php echo $form->error($model,'distance_radius_short_stop'); ?> </div>
  </div>
  <div class="clear"></div>
  <div class="row-parameter-single">
    <div class="parameter-label"> <?php echo $form->labelEx($model,'time_radius_short_stop'); ?> </div>
    <div class="parameter-input"> <?php echo $form->textField($model,'time_radius_short_stop',array('size'=>60,'maxlength'=>500)); ?> s</div>
    <div id="information-icon" class="information-icon"> </div>
    <div id="parameter-information-box" class="parameter-information-box"> </div>
    <div class="clear"></div>
    <div class="parameter-error"> <?php echo $form->error($model,'time_radius_short_stop'); ?> </div>
  </div>
  <div class="buttons">
    <div id="upload-continue-button" class="upload-continue-button"></div>       
     <?php echo CHtml::ajaxSubmitButton('Continue',CHtml::normalizeUrl(array('parameterForm/update','render'=>true)),
         array(
           'dataType'=>'json',
           'type'=>'post',
           'success'=>'function(data) {
              if(data.status=="success"){
                display_file_in_process();
              }
              else{
                $.each(data, function(key, val) {
                  $("#parameters-form #"+key+"_em_").text(val);                                                    
                  $("#parameters-form #"+key+"_em_").show();
                });
                display_form();
              }       
            }',                    
           'error'=>'function(){
             alert("We are sorry. There was a problem sending your data.");
           }',
           'beforeSend'=>'function(){                        
               display_screen_loading();
            }'
         ),
         array(
          'id'=>'submitButton',
          'class'=>'submit_button',
          'style'=>'display:none;',
         )
       );
     ?>
    </div>  
    <!--Termina el widget -->
  <?php $this->endWidget();?>
</div>  











