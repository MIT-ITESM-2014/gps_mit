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

<body>

<div class="" id="page">

	<div id="header">
		<div id="logo">
			<a href="index.php" ><div id="logo-image"> </div></a>
		</div>
		<div id="icons-bar">
			<div id="routes-section"></div>
			<div id="trucks-section"> </div>
			<div id="stats-section"> </div>	
		</div>	
	</div><!-- header -->

	<div class="clear"></div>

	<div id="routes-selection"> 
		
		<div id="selector-truck">
			<div id="truck-icon"> </div>
			<div id="truck-selector-container">
        <select id="truck_selector" name="truck_selector">
        </select>
      </div>
		</div>
		<div id="selector-day">
			<div id="day-icon"> </div>
			<div id="date-route" name="date-route">
			</div>
		</div>
		<div id="selector-route">
			<div id="route-icon"> </div>
			<select id="select-route" name="select-route"> 
			</select>
		</div>
		
		<div id="button_update_map" name="button_update_map" class="update-button-map">
      <p id ="update-map-text"> Update </p>
    </div>
	</div>

	<?php echo $content; ?>
	

	<div class="clear"></div>
<!--
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by CTL.<br/>
		All Rights Reserved.<br/>
	</div>
-->

</div><!-- page -->

</body>
</html>
