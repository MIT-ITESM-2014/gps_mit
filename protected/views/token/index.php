<?php
/* @var $this TokenController */
/* @var $dataProvider CActiveDataProvider */

Yii::import('application.extensions.gmap.*');


$this->breadcrumbs=array(
	'Tokens',
);

$this->menu=array(
	array('label'=>'Create Token', 'url'=>array('create')),
	array('label'=>'Manage Token', 'url'=>array('admin')),
);
?>

<h1>Tokens</h1>

<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d11791.88623394304!2d-71.103187!3d42.3644454!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sus!4v1402706097467" width="600" height="450" frameborder="0" style="border:0"></iframe>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); 

/*$gMap = new EGMap();

$gMap->zoom = 10;
$mapTypeControlOptions = array(
  'position'=> EGMapControlPosition::LEFT_BOTTOM,
  'style'=>EGMap::MAPTYPECONTROL_STYLE_DROPDOWN_MENU
);
 
$gMap->mapTypeControlOptions= $mapTypeControlOptions;
 
$gMap->setCenter(39.721089311812094, 2.91165944519042);
 
// Create GMapInfoWindows
$info_window_a = new EGMapInfoWindow('<div>I am a marker with custom image!</div>');
$info_window_b = new EGMapInfoWindow('Hey! I am a marker with label!');
 
$icon = new EGMapMarkerImage("http://google-maps-icons.googlecode.com/files/gazstation.png");
 
$icon->setSize(32, 37);
$icon->setAnchor(16, 16.5);
$icon->setOrigin(0, 0);
 
// Create marker
$marker = new EGMapMarker(39.721089311812094, 2.91165944519042, array('title' => 'Marker With Custom Image','icon'=>$icon));
$marker->addHtmlInfoWindow($info_window_a);
$gMap->addMarker($marker);
 
// Create marker with label
$marker = new EGMapMarkerWithLabel(39.821089311812094, 2.90165944519042, array('title' => 'Marker With Label'));
 
$label_options = array(
  'backgroundColor'=>'yellow',
  'opacity'=>'0.75',
  'width'=>'100px',
  'color'=>'blue'
);*/
 
/*
// Two ways of setting options
// ONE WAY:
$marker_options = array(
  'labelContent'=>'$9393K',
  'labelStyle'=>$label_options,
  'draggable'=>true,
  // check the style ID 
  // afterwards!!!
  'labelClass'=>'labels',
  'labelAnchor'=>new EGMapPoint(22,2),
  'raiseOnDrag'=>true
);
 
$marker->setOptions($marker_options);
*/
 
// SECOND WAY:
/*$marker->labelContent= '$425K';
$marker->labelStyle=$label_options;
$marker->draggable=true;
$marker->labelClass='labels';
$marker->raiseOnDrag= true;
 
$marker->setLabelAnchor(new EGMapPoint(22,0));
 
$marker->addHtmlInfoWindow($info_window_b);
 
$gMap->addMarker($marker);
 
// enabling marker clusterer just for fun
// to view it zoom-out the map
$gMap->enableMarkerClusterer(new EGMapMarkerClusterer());
 
$gMap->renderMap();*/

?>
