<?php
date_default_timezone_set("America/New_York");
defined('YII_DEBUG') or define('YII_DEBUG',true);

// including Yii
$yii=dirname(__FILE__).'/yii/framework/yii.php';
require_once($yii);
 
// we'll use a separate config file
$config=dirname(__FILE__).'/protected/config/cron_main.php';
Yii::createConsoleApplication($config)->run();

?>
