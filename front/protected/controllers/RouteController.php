<?php

class RouteController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			//array('allow',  // allow all users to perform 'index' and 'view' actions
			//	'actions'=>array(),
			//	'users'=>array('*'),
			//),
			//array('allow', // allow authenticated user to perform 'create' and 'update' actions
			//	'actions'=>array('index','view','admin'),
			//	'users'=>array('@'),
			//),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'getRoute', 'getRouteList', 'getRouteStats'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') == 0)",
			),
			//array('allow', // allow admin user to perform 'admin' and 'delete' actions
			//	'actions'=>array('delete','create','update',),
			//	'users'=>array('admin'),
			//),
			//array('deny',  // deny all users
			//	'users'=>array('*'),
			//),
		);
	}
	
	
	public function actionGetRouteList()
  {

    if(!Yii::app()->user->hasState('user'))
      return " ";

    header('Content-type: application/json');
    $truck_id = $_GET["truck_id"];
    $date=$_GET["start_date"];
    str_replace ( "/" , "-" , $date );
    
    $criteria = new CDbCriteria();
    $criteria->condition = "truck_id=".$truck_id;
    $criteria->addBetweenCondition('datetime', $date." 00:00:00.0", $date." 23:59:59.999");
    $criteria->distinct = true;
    $criteria->select = array(
      't.route_id',
    );
    $route_ids = Sample::model()->findAll($criteria);
    $routes = array();
    //print_r($route_ids);
    //error_log();
    foreach($route_ids as $route_id)
    {
      //print_r("La ruta es : ".$route_id->route_id);
      //error_log(print_r($route_id));
      if($route_id->route_id != null)
        $routes[] = Route::model()->findByPk($route_id->route_id);
    }
    if(count($routes)>0)
    {
      if($routes[0] != null)
      {
        $json_data = '{"routes":[';
        $comma_counter = 0;
        
        foreach($routes as $route)
        {
          if($comma_counter != 0)
            $json_data = $json_data . ",";

          $json_data = $json_data . '{"name":"'.$route->name.'", "value":"'.$route->id.'"}';
          $comma_counter++;
        }

        $json_data = $json_data . ']}';
        echo CJSON::encode($json_data);
      }
    }
    Yii::app()->end(); 
  }
  
  /*Second function with new parameters*/
  public function actionGetRoute()
  {
    //header('Content-type: application/json');
	  //$trucks = Truck::model()->findAll();
	 
    if(!Yii::app()->user->hasState('user'))
      return " ";    

    $script="";

	  if(isset($_GET['route_id']))
	  {
	    if(!empty($_GET['route_id']))
	    {
	      $route_id = $_GET['route_id'];
	      $criteria = new CDbCriteria();
        $criteria->addCondition('t.id = '.$route_id);
        $criteria->with = array('samples');
        $routes = Route::model()->findAll($criteria);
        $samples = $routes[0]->samples;
        $script = "";
        $script = $script . "
        routeCoordinates = [";
        foreach($samples as $sample)
        {
          $script = $script." new google.maps.LatLng( ".$sample->latitude.", ".$sample->longitude." ),\n";
        }  
        $script = $script."];
        route.setPath(routeCoordinates);
        ";
      }
    }
    echo $script;
    Yii::app()->end();
  }
  
  public function actionGetRouteStats()
  {
    
    if(!Yii::app()->user->hasState('user'))
      return " ";

    header('Content-type: application/json');
    $json_data = "";

    if(isset($_GET["route_id"]))
    {
      if(!empty($_GET["route_id"]))
      {
        
        $route_id = $_GET["route_id"];
        
        $criteria = new CDbCriteria();
        $criteria->condition = "t.id=".$route_id;
        $routes = Route::model()->findAll($criteria);
        $route = $routes[0];

        $criteria2 = new CDbCriteria();
        $criteria2->condition = "t.route_id=".$route_id;
        $samples = Sample::model()->findAll($criteria2);
        $sample = $samples[0];

        $truck = Truck::model()->findByPk($sample->truck_id);

        //rounding and concatenating with units
         $distance_trimmed = round($route->distance*100)/100;
         $distance_string = (string) $distance_trimmed . " km";

         $average_speed_trimmed = round($route->average_speed * 100)/100;
         $average_speed_string = (string) $average_speed_trimmed . " km/h";

        //changes in time covered by route
        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        $time_days = floor($route->time / $secondsInADay);

        $hourSeconds = $route->time % $secondsInADay;
        $time_hours = floor($hourSeconds / $secondsInAnHour);

        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $time_minutes = floor($minuteSeconds / $secondsInAMinute);

        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $time_seconds = ceil($remainingSeconds);

        if($time_days > 0)
        {
          $total_duration_string = (string) $time_days . " d " . (string) $time_hours . " h " . (string) $time_minutes . " min";
        }
        else
        {
          $total_duration_string = (string) $time_hours . " h " . (string) $time_minutes . " min";
        }

        //date parser
        $source = $sample->datetime;
        $date = new DateTime($source);
        //modifying json
        //added fields (general_information)
        $json_data = '{"general_information": {"truck_id": "'.$truck->name.'", "route_id": "'.$route->name.'", "date": "'.$date->format('M d, Y').'"},"route_stats":{ "distance" : "'.$distance_string.'", "average_speed" : "'. $average_speed_string.'", "short_stops_count" : "'.$route->short_stops_count.'", "duration": "'.$total_duration_string.'"}}';
      }
    }
    echo CJSON::encode($json_data);
    Yii::app()->end();
  }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));

    if(Yii::app()->user->hasState('companies_count'))
    {
      if(Yii::app()->user->getState('companies_count')<=0)
      {
        $this->render('no_company',array());
      }
      if(Yii::app()->user->hasState('current_company'))
      {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');    
        $cs->registerCoreScript('screen-block'); 

	      $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
	      $trucks_ids = array();
        foreach($trucks as $truck)
          $trucks_ids[] = $truck->id;
	      $truck_id = null;
	      $samples = null;
	      
	      if(count($trucks) > 0)
	      {
	        $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
	        $truck_id = $trucks[0]->id;
          $criteria->addCondition('truck_id = '.$truck_id);
          $samples = Sample::model()->findAll($criteria);
        }
        //Get the min_date
        $criteria_min_date = new CDbCriteria();
        $criteria_min_date->select='min(datetime) as min_date';
        $criteria_min_date->addInCondition('truck_id', $trucks_ids);
        $min_date_sample = Sample::model()->findAll($criteria_min_date);
        $min_date = null;
        if(!empty($min_date_sample))
        {
          $date = new DateTime($min_date_sample[0]->min_date);
          $min_date = $date->format('m/d/Y');  
        }
        
        //Get the max_date
        $criteria_max_date = new CDbCriteria();
        $criteria_max_date->select='max(datetime) as max_date';
        $criteria_max_date->addInCondition('truck_id', $trucks_ids);
        $max_date_sample = Sample::model()->findAll($criteria_max_date);
        $max_date = null;
        if(!empty($max_date_sample))
        {
          $date = new DateTime($max_date_sample[0]->max_date);
          $max_date = $date->format('m/d/Y');
        }
        
        //TODO Disable the calendar when no dates available
        if($max_date == null)
          $max_date = date('m/d/Y');
        if($min_date == null)
          $min_date = date('m/d/Y');
        
        //Get the list of unavailable dates
        $criteria_active_days = new CDbCriteria(array('order'=>'active_day ASC'));
        $criteria_active_days->select='distinct DATE(datetime) as active_day';
        $active_days_sample = Sample::model()->findAll($criteria_active_days);
        $old_date = null;
        if(!empty($active_days_sample))
          $old_date = new DateTime($active_days_sample[0]->active_day);
        $inactive_days = array();
        foreach($active_days_sample as $ads)
        {
          $new_day = new DateTime($ads->active_day);
          $diff = (int)($old_date->diff($new_day)->format('%R%a'));
          while( $diff > 2  )//More than one day distance
          { 
            $old_date->modify('+1 day');
            $inactive_days[] = $old_date->format('m/d/Y');
            $diff = (int)($old_date->diff($new_day)->format('%R%a'));
          }
          $old_date = $new_day;
        }
        $inactive_days_string = "";
        foreach($inactive_days as $id)
          $inactive_days_string = $inactive_days_string . "'" . $id ."',"; 
        
        //Script variables
        $map_center = "";
        if(!empty($samples))
          $map_center = $samples[0]->latitude.", ".$samples[0]->longitude;
        $route_coordinates = "";
        if(!empty($samples))
          foreach($samples as $sample)
            $route_coordinates = " new google.maps.LatLng( ".$sample->latitude.", ".$sample->longitude." ),\n";
          
        
        //DEfine center
	      $script = "
	        var temporal_script = null;
	        var route;
	        var map;
	        //var routeCoordinates;
	        var routeCoordinates2;
	        
	        function initialize() {
            var mapOptions = {
            zoom: 12,
            center: new google.maps.LatLng(".$map_center.")
          };
          
          map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
          //personalize map style
          map.set('styles', [
        {
            featureType: 'water',
            stylers: [
                { visibility: 'on' },
                { color: '#acbcc9' }
            ]
        },
        {
            featureType: 'landscape',
            stylers: [
                { color: '#FBF7F1' }
            ]
        },
        {
            featureType: 'road.highway',
            elementType: 'geometry',
            stylers: [
                { color: '#B2AFA7' }
            ]
        },
        {
            featureType: 'road.arterial',
            elementType: 'geometry',
            stylers: [
                { color: '#D4D4D4' }
            ]
        },
        {
            featureType: 'road.local',
            elementType: 'geometry',
            stylers: [
                { color: '#D4D4D4'},
                { weight: 0.5 }
            ]
        },
        {
            featureType: 'poi.park',
            elementType: 'geometry',
            stylers: [
                { color: '#c5dac6' }
            ]
        },
        {
            featureType: 'administrative',
            stylers: [
                { visibility: 'on' },
                { lightness: 33 }
            ]
        },
         {
            featureType: 'poi',
            elementType: 'labels',
            stylers: [
                { visibility: 'off' },
                { lightness: 0 }
            ]
        },
        {
            featureType: 'road,highway',
            stylers: [
                { lightness: 20 }
            ]
        },
          {
            featureType: 'road',
            elementType: 'labels',
            stylers: [
                { lightness: -10 },
                { saturation: -100 },
              ]
        }
  ]);
        
        document.getElementById(\"button_update_map\").onclick = function() {
          button_update_map_action(); 
        };
        
        map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(
          document.getElementById('map-legend'));
        
        
        //Load a polyline by hand
        routeCoordinates = [".$route_coordinates."];
        
        route = new google.maps.Polyline({
          path: routeCoordinates,
          geodesic: true,
          strokeColor: '#49CEAE',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });
      
        route.setMap(map);
        
        var trafficLayer = new google.maps.TrafficLayer();
        //trafficLayer.setMap(map);
      }

      function loadScript() {
        
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' + 'callback=initialize';
        document.body.appendChild(script);
      }



      function update_stats()
      {
        $.ajax({ 
            type: \"GET\",
            dataType: \"json\",
            url: \"index.php?r=route/getRouteStats&route_id=\"+document.getElementById(\"select-route\").value,
            success: function(data){
              var parsed_data = $.parseJSON(data);
              if(parsed_data != null)
              {
                $('#route-information-truck-name').empty();
                $('#route-information-truck-name').append(parsed_data['general_information']['truck_id']);
                $('#route-information-route-id').empty();
                $('#route-information-route-id').append(parsed_data['general_information']['route_id']);
                $('#route-information-date').empty();
                $('#route-information-date').append(parsed_data['general_information']['date']);
                $('#distance_data_container').empty();
                $('#distance_data_container').append(parsed_data['route_stats']['distance']);
                $('#time_data_container').empty();
                $('#time_data_container').append(parsed_data['route_stats']['duration']);
                $('#average_speed_data_container').empty();
                $('#average_speed_data_container').append(parsed_data['route_stats']['average_speed']);
                $('#short_stops_count_data_container').empty();
                $('#short_stops_count_data_container').append(parsed_data['route_stats']['short_stops_count']);
              }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              alert(xhr.statusText);
              alert(thrownError);
            }   
        });
      }
      
      //Remove polyline
      function button_update_map_action()
      {
        if(temporal_script !== null)
        {
          temporal_script.remove();
        }
        
        var script = document.createElement(\"script\");
        script.type = \"text/javascript\";
        script.src = \"index.php?r=route/getRoute&route_id=\"+document.getElementById(\"select-route\").value;
        temporal_script = document.body.appendChild(script);
        update_stats();
        $('#truck-selection-help').hide('fast');
      }
      
      
      window.onload = loadScript;";
		  $this->render('index',array(
			  //'dataProvider'=>$dataProvider,
			  'script'=>$script,
			  'model'=>new Sample(),
			  'min_date'=>$min_date,
			  'max_date'=>$max_date,
			  'inactive_days_string'=>$inactive_days_string,
			  'trucks'=>$trucks,
		  ));  
		  }
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Route the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Route::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Route $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='route-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
