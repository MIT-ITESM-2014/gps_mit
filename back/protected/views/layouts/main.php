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

  <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>  
  
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo">
			<a href="index.php" ><div id="logo-image"> </div></a>
		</div>
	</div><!-- header -->

	<div id="page-contents">
		<div id="mainmenu-container">
			<div id="mainmenu">
				<?php 
				  if(Yii::app()->user->hasState('isAdmin'))
				  {
				    if(Yii::app()->user->getState('isAdmin') == 1)
				    {
				      $this->widget('zii.widgets.CMenu',array(
					      'encodeLabel'=>false,
					      'items'=>array(
						      array('label'=>'<div id="identity-image"> </div> Users', 'url'=>array('/identity/admin')),
						      array('label'=>'<div id="fleet-image"> </div> Fleet', 'url'=>array('/company/admin')),
						      array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'))
					      ), 'id' => 'menu-elements' 
				      ));
				    }
				    elseif(Yii::app()->user->getState('companies_count') > 0)
				    {
				      $this->widget('zii.widgets.CMenu',array(
					      'encodeLabel'=>false,
					      'items'=>array(
						      //array('label'=>'Home', 'url'=>array('/site/index')),
						      array('label'=>'<div id="change-fleet-image"> </div> '.Yii::app()->user->getState('current_company_name').'', 'url'=>array('/company/change')),
						      array('label'=>'<div id="upload-image"> </div> Upload CSV', 'url'=>array('/sample/create')),
						      array('label'=>'<div id="sample-image"> </div> Sample', 'url'=>array('/sample/admin')),
						      //array('label'=>'Token', 'url'=>array('/token/index')),
						
						      array('label'=>'<div id="truck-image"> </div> Truck', 'url'=>array('/truck/admin')),
						      array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'))
					      ), 'id' => 'menu-elements' 
				      ));
				    }
				  }
			  ?>
			</div><!-- mainmenu -->
		</div>
		<?php echo $content; ?>
		<div class="clear"> </div>
	</div>
</div><!-- page -->

</body>
</html>
