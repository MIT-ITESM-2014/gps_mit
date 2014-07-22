<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>



<div id="contents-field">


	<div class="headers">
		<h1>Welcome to <?php echo CHtml::encode(Yii::app()->name); ?> </h1>
	</div>	
  <div id="no-companies-message">
	  <?php
      if( ( Yii::app()->user->getState('isAdmin')==0 ) && !Yii::app()->user->hasState('current_company') )
        echo "There are no companies available at the moment"
    ?>
  </div>
	<div id="app-watermark">
  
	</div>	

</div>

