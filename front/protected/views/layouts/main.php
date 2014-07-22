<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body style="width:100%; height:100%;">
<div class="" id="page" style="min-width:1250px; width: 100%; overflow:hidden; position:relative; min-height:750px; height:100%; display:inline-block; float:left;">

	  <div id="header" style="min-width:1250px; width:100%; overflow:hidden; position:relative; height:70px; ">
		  <div id="logo">
			  <a href="<?php echo Yii::app()->createUrl('company/change', array())?>"><div id="logo-image"> </div></a>
		  </div>
		  <div id="session-info">
			  <div id="session-username" class=""><?php echo Yii::app()->user->hasState('first_name') ? Yii::app()->user->getState('first_name') : "User"; ?></div>
			  <a href="<?php echo Yii::app()->createUrl('site/logout', array())?>"> <div id="logout-button"> </div></a>
			  <div id="session-fleet" class=""><?php echo Yii::app()->user->hasState('current_company_name') ? Yii::app()->user->getState('current_company_name') : "Fleet"; ?></div>
			  <a href="<?php echo Yii::app()->createUrl('company/change', array())?>"> <div id="change-company-button"> </div></a>
		  </div>
		  <div id="icons-bar">
			  <a href="<?php echo Yii::app()->createUrl('site/index', array())?>"><div id="routes-section" class="routes-section"></div></a>
			  <a href="<?php echo Yii::app()->createUrl('site/trucks', array())?>"><div id="trucks-section" class="trucks-section"> </div></a>
			  <a href="<?php echo Yii::app()->createUrl('site/stats', array())?>"><div id="stats-section" class="stats-section"> </div></a>
		  </div>		
	  </div><!-- header -->
    <div id="content-container" style="min-width:1250px; width:100%; overflow:hidden; position:relative; min-height:680px;">
	  <?php echo $content; ?>
	  </div>
	  <div class="clear"></div>
  </div><!-- page -->

</body>
</html>
<?php
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/site/fill_window.js',CClientScript::POS_END);
?>
