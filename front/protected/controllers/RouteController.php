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
				'actions'=>array('create','update','index','view', 'getRoute', 'getRouteList', 'getRouteStats'),
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
    echo $script;
    Yii::app()->end();
  }
  
  public function actionGetRouteStats()
  {
    
    header('Content-type: application/json');
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
    echo CJSON::encode($json_data);
    Yii::app()->end(); 
  }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Route;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Route']))
		{
			$model->attributes=$_POST['Route'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Route']))
		{
			$model->attributes=$_POST['Route'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
    //$rows = user::model()->findAllByAttributes($user, $criteria);
	  $trucks = Truck::model()->findAll();
	  $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
	  $truck_id = $trucks[0]->id;
    $criteria->addCondition('truck_id = '.$truck_id);
    $criteria->addBetweenCondition('datetime', '2013-07-06', '2013-07-07');
	  $samples = Sample::model()->findAll($criteria);
	  
	  $criteria2 = new CDbCriteria(array('order'=>'datetime ASC'));
	  $truck_id2 = $trucks[6]->id;
    $criteria2->addCondition('truck_id = '.$truck_id2);
    $criteria2->addBetweenCondition('datetime', '2013-09-27', '2013-09-28');
	  $samples2 = Sample::model()->findAll($criteria2);
    
    //Get the min_date
    $criteria_min_date = new CDbCriteria();
    $criteria_min_date->select='min(datetime) as min_date';
    $min_date_sample = Sample::model()->findAll($criteria_min_date);
    //$min_date = $min_date_sample[0]->min_date;
    $date = new DateTime($min_date_sample[0]->min_date);
    $min_date = $date->format('m/d/Y');
    
    //Get the max_date
    $criteria_max_date = new CDbCriteria();
    $criteria_max_date->select='max(datetime) as max_date';
    $max_date_sample = Sample::model()->findAll($criteria_max_date);
    //$max_date = $max_date_sample[0]->max_date;
    $date = new DateTime($max_date_sample[0]->max_date);
    $max_date = $date->format('m/d/Y');
    
    //TODO Disable the calendar when no dates available
    if($max_date == null)
    {
      $max_date = date('m/d/Y');
    }
    if($min_date == null)
    {
      $min_date = date('m/d/Y');
    }
    
    //Get the list of unavailable dates
    
    $criteria_active_days = new CDbCriteria(array('order'=>'active_day ASC'));
    $criteria_active_days->select='distinct DATE(datetime) as active_day';
    $active_days_sample = Sample::model()->findAll($criteria_active_days);
    //$old_date = strtotime($active_days_sample[0]->active_day); //Temporary useless value
    $old_date = new DateTime($active_days_sample[0]->active_day);
    $inactive_days = array();
    foreach($active_days_sample as $ads)
    {
      $new_day = new DateTime($ads->active_day);
      $diff = (int)($old_date->diff($new_day)->format('%R%a'));
      while( $diff > 2  )//More than one day distance
      { 
        $old_date->modify('+1 day');
        print_r("--".$old_date->format('m/d/Y')."--");
        $inactive_days[] = $old_date->format('m/d/Y');
        $diff = (int)($old_date->diff($new_day)->format('%R%a'));
      }
      
      $old_date = $new_day;
      
    }
    
    $inactive_days_string = "";
    foreach($inactive_days as $id)
    {
      $inactive_days_string = $inactive_days_string . "'" . $id ."',"; 
    }
    
	  $script = "
	    var temporal_script = null;
	    var route;
	    var map;
	    var routeCoordinates;
	    var routeCoordinates2;
	    
	    function initialize() {
        var mapOptions = {
        zoom: 12,
        center: new google.maps.LatLng(".$samples2[0]->latitude.",".$samples2[0]->longitude.")
      };

      map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
      //personalize map style
      map.set('styles', [
        {
          featureType: 'road.local',
          elementType: 'geometry',
          stylers: [
            {color: '#96A9C1'},
            { visibility: 'simplified'},
            { weight: 0.9 }
          ]
        },
      {
        featureType: 'road.highway',
        elementType: 'geometry',
        stylers: [
          {color: '#F7DC9E'},
          { visibility: 'simplified'},
          { weight: 5.5 }
        ]
      },
      {
        featureType: 'road',
        elementType: 'labels',
        stylers: [
          { visibility: 'on' },
          { saturation: 600 }
        ]
      },  
      {
        featureType: 'landscape',
        elementType: 'geometry',
        stylers: [
          { hue: '#ffff00' },
          { gamma: 0.5 },
          { saturation: 82 },
          { lightness: 96 }
          ]
      },
      {
        featureType: 'poi.government',
         elementType: 'geometry',
          stylers: [
            { visibility: 'on' },
            { hue: '#9AB896' },
            { lightness: -15 },
            { saturation: 99 }
          ]
        }
      ]);
      
      document.getElementById(\"button_update_map\").onclick = function() {
        button_update_map_action(); 
      };
      //document.getElementById(\"button_two\").onclick = function() {
      //  button_two_action(); 
      //};
      //document.getElementById(\"button_three\").onclick = function() {
      //  button_three_action(); 
      //};
      //document.getElementById(\"button_four\").onclick = function() {
      //  button_four_action(); 
      //};
      //document.getElementById(\"button_five\").onclick = function() {
      //  button_five_action(); 
      //};
      //Add icon
      //var iconBase = 'http://www.miamidade.gov/transit/mobile/images/';
      //var myLatLng = new google.maps.LatLng(-33.44586, -70.76714 )
      //var marker = new google.maps.Marker({
        //position: myLatLng,
        //map: map,
        //icon: iconBase + 'icon-Bus-Stop.png'
      //});
      map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(
      document.getElementById('map-legend'));

      //LEGEND
      //var legend = document.getElementById('map-legend');

      //GEOJSON
      //map.data.loadGeoJson('http://localhost/gps_mit/front/js/maps/geojson_test.json');
      
      //Load a polyline by hand
      routeCoordinates = [";
      foreach($samples as $sample)
      {
        $temp_string = " new google.maps.LatLng( ".$sample->latitude.", ".$sample->longitude." ),\n";
        $script = $script.$temp_string;
      }  
      $script = $script."];

      //Load a polyline by hand
      routeCoordinates2 = [";
      foreach($samples2 as $sample2)
      {
        $temp_string2 = " new google.maps.LatLng( ".$sample2->latitude.", ".$sample2->longitude." ),\n";
        $script = $script.$temp_string2;
      }
      $script = $script."];
      
      var color = generateRandomColor();

      route = new google.maps.Polyline({
        path: routeCoordinates2,
        geodesic: true,
        strokeColor: color,//'#C030FF',
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
    
      route.setMap(map);
      
      var trafficLayer = new google.maps.TrafficLayer();
      trafficLayer.setMap(map);
    }

    function loadScript() {
      
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' + 'callback=initialize';
      document.body.appendChild(script);
    }
    
    //Add polyline
    function button_two_action()
    {
      route.setMap(map);
    }
    
    //Change polyline
    function button_three_action()
    {
      route.setPath(routeCoordinates);
    }
    
    //Change polyline
    function button_four_action()
    {
      route.setPath(routeCoordinates2);
    }

    function generateRandomColor(){

      return '#'+Math.floor(Math.random()*16777215).toString(16);
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

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Route('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Route']))
			$model->attributes=$_GET['Route'];

		$this->render('admin',array(
			'model'=>$model,
		));
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
