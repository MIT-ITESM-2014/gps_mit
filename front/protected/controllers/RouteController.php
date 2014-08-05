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
				'actions'=>array('index', 'getRoute', 'getRouteList', 'getRouteStats', 'getAvailableDates'),
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
	
  public function actionGetAvailableDates()
  {
   
   if(!Yii::app()->user->hasState('user'))
      return " ";  

    header('Content-type: application/json');
    $truck_id = $_GET["truck_id"];

    //Get the list of available dates
    $available_dates = array();
    //foreach(Truck::model()->findByPk($truck_id)->routes as $route)
    //{
    //  $new_date = new DateTime($route->firstSample[0]->datetime);
    //  $available_dates[(string)$new_date->format('m/d/Y')] = 1;
    //}
    
    
    
    
    $list= Yii::app()->db->createCommand('SELECT DISTINCT(to_char(datetime,\'Mon/DD/YYYY\')) as d FROM sample WHERE route_id IN ( SELECT id FROM route WHERE truck_id = :truck_id AND is_valid = 1 ) ORDER BY d ASC;')->bindValue('truck_id',$truck_id)->queryAll();

    foreach($list as $item){
        //process each item here
        $available_dates[$item['d']] = 1;

    }
    error_log(print_r($list, true));


    
    

    //Search for min and max date
    reset($available_dates);
    $min_date = new DateTime(key($available_dates));
    $max_date = new DateTime(key($available_dates));
    foreach($available_dates as $available_date_string=>$active)
    {
      $new_date_model = new DateTime($available_date_string);

      if($new_date_model< $min_date)
        $min_date = $new_date_model;
      if($new_date_model> $max_date)
        $max_date = $new_date_model;
    }
    //Generate list of unavailable dates
    $current_date = new DateTime($min_date->format('m/d/Y'));
    $loop_diff =  (int)($current_date->diff($max_date)->format('%R%a'));
    $unavailable_dates = array();

    while($loop_diff > 0)
    {
      $current_date->modify('+1 day');
      $current_date_string = (string)$current_date->format('m/d/Y');
      if(!isset($available_dates[$current_date_string]))
      {
        $unavailable_dates[] = $current_date_string;
      }
      
      $loop_diff = (int)($current_date->diff($max_date)->format('%R%a'));
    }
    if(!empty($min_date) && !empty($max_date) && !empty($unavailable_dates))
    {
      echo CJSON::encode(array("min_date"=>$min_date->format('m/d/Y'), "max_date" => $max_date->format('m/d/Y'), "inactive_days" => $unavailable_dates));
    }
    else
      echo CJSON::encode("");   

    Yii::app()->end(); 
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

    foreach($route_ids as $route_id)
    {

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
        
        //Script variables
        $map_center = "";
        if(!empty($samples))
          $map_center = $samples[0]->latitude.", ".$samples[0]->longitude;
        $route_coordinates = "";
        if(!empty($samples))
          foreach($samples as $sample)
            $route_coordinates = " new google.maps.LatLng( ".$sample->latitude.", ".$sample->longitude." ),\n";
          
        $options = "";
        foreach ($trucks as $t)
        {
          $options = $options . " newOption = $('<option value=\"".$t->id."\">".$t->name."</option>');  $('#truck_selector').append(newOption);";
        }

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
      

      
      window.onload = loadScript;


  var newOption;
  ".$options."
  $('#truck_selector').append(newOption);
  
$('#routes-section').removeClass('routes-section').addClass('active_routes');

$('#truck_selector').prepend('<option>Chooose a truck</option>');

$('#select-route').prepend('<option>Choose a trip</option>');

$('#truck_selector').change(updateAvailableDate);

$('#choose_date_dp').change(updateRouteList);

var opt = $('#truck_selector option:eq(1)').val();
$('#truck_selector').val(opt); 
$('#truck_selector').change();


//does not display any dates unless a truck is picked.
/*$('#choose_date_dp').datepicker({
       beforeShowDay: function (date) {
       if (date.getDate() == 0) {
           return [true, ''];
       }
       return [false, ''];
    }
});*/

var inactive_days;

function disableDates(date)
{
  var disabledDates = inactive_days;
  for (var i = 0; i < disabledDates.length; i++) {
    if (new Date(disabledDates[i]).toString() == date.toString())
    {
      return [false, '', ''];
    }
  }
  return [true, '', ''];
}

function updateAvailableDate()
{
  $.ajax({
    type: 'GET',
    dataType: 'JSON',
    url: 'index.php?r=route/getAvailableDates&truck_id=' + document.getElementById('truck_selector').value, 
    success: function(data)
    {
      var min_date = data.min_date;
      var max_date = data.max_date;
      inactive_days = data.inactive_days;

      var choose_date_dp = $('#choose_date_dp');
      var selectedDate = $('#choose_date_dp').val();

      choose_date_dp.datepicker('option', 'maxDate', max_date);
      choose_date_dp.datepicker('option', 'minDate', min_date);
      
      choose_date_dp.datepicker('option', 'beforeShowDay', disableDates);
      
      if(!selectedDate)
      { 
        selectedDate = choose_date_dp.datepicker('option', 'all').minDate;
      }

      choose_date_dp.datepicker('setDate', selectedDate);

      updateRouteList();
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.statusText);
      alert(thrownError);
    }   

  });
}

function updateRouteList()
{
  $.ajax({ 
          type: 'GET',
          dataType: 'JSON',
          url: 'index.php?r=route/getRouteList&truck_id='+document.getElementById('truck_selector').value+'&start_date='+document.getElementById('choose_date_dp').value,
          success: function(data){        
            $('#select-route').find('option').remove();
            var parsed_data = $.parseJSON(data);
            $.each(parsed_data['routes'], function( index, value ) {
              $('#select-route').append('<option value=\"'+value['value']+'\">'+value['name']+'</option>');
            });
            $('#button_update_map').click();
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
          }   
      });

}

      ";
		  $this->render('index',array(
			  //'dataProvider'=>$dataProvider,
			  'script'=>$script,
			  'model'=>new Sample(),
			  //'min_date'=>$min_date,
			  //'max_date'=>$max_date,
			  //'inactive_days_string'=>$inactive_days_string,
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
