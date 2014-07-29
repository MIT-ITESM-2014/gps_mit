<?php

//Importing extension for asynchronous processing
Yii::import('ext.runactions.components.ERunActions');

class SampleController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array( 'uploadOne', 'createPartial', 'submitParameters', 'sendParameters', 'admin','delete', 'create', 'processData', 'recalculateData'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') != 1)"
			),
		);
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function createNewParametersForm()
	{
	  $parameter_form_model = new ParameterForm;
	  $company_model = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
	  
	  if($company_model->time_radius_short_stop == null)
	    $parameter_form_model->time_radius_short_stop = 50;
	  else
	    $parameter_form_model->time_radius_short_stop = $company_model->time_radius_short_stop;
	  
	  if($company_model->distance_radius_short_stop == null)
	    $parameter_form_model->distance_radius_short_stop = 0.05;
	  else
	    $parameter_form_model->distance_radius_short_stop = $company_model->distance_radius_short_stop;
	  
	  if($company_model->time_radius_long_stop == null)
	    $parameter_form_model->time_radius_long_stop = 14400;
	  else
	    $parameter_form_model->time_radius_long_stop = $company_model->time_radius_long_stop;
	  
	  if($company_model->distance_radius_long_stop == null)
	    $parameter_form_model->distance_radius_long_stop = 0.1;
	  else
	    $parameter_form_model->distance_radius_long_stop = $company_model->distance_radius_long_stop;
	  
	  return $parameter_form_model;
	}
	
	public function actionCreate()
	{
	  if((!Yii::app()->user->isGuest) && (Yii::app()->user->hasState('current_company')))
	  {
	    $company_id = Yii::app()->user->getState('current_company');
	    $company_model = Company::model()->findByPk($company_id);
	    
		  $step = 0;

		  if($company_model->has_file_in_process == 1)
		  {
		    $pending_files = $company_model->uploaded_files;
	      if(count($pending_files) > 0)
	        $step = $pending_files[0]->step;
		  }
		
		  if($step == 2)
		    $this->render('file_in_process',array());
		  else
		  {
		    $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload.full.min.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload_init.js', CClientScript::POS_END);
	      $parameter_model = $this->createNewParametersForm();
        $script = isset($_GET['script']);
        	    
		    $this->render('create',array(
          'step'=>$step,
          'parameter_model'=>$parameter_model,
          'script'=>$script
	      ));
	    }
	  }
	  else
	    $this->redirect(array('login/'));
	}
	
	function calculateAllMetrics()
  {
    error_log("step samplings");
    $this->actionFindSamplings();
    error_log("s stopand route");
    $this->actionFindStopsAndRoutes();
    error_log("s route");
    $this->actionGenerateRouteMetrics();
    error_log("s truck");
    $this->actionGenerateTruckMetrics();
    error_log("s company");
    $this->actionGenerateCompanyMetrics();
    error_log("s sd");
    $this->actionGenerateStandardDeviation();
  }
  
  function actionGenerateStandardDeviation()
  {
    $company = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
    $company_trip_count = $company->route_count;
    
    $company_average_stop_count_per_trip_sds = 0.0;
    $company_average_trip_distance_sds = 0.0;
    $company_average_stem_distance_sds = 0.0;
    $company_average_speed_sds = 0.0;
    
    foreach( $company->trucks as $truck )
    {
      $truck_trip_count = $truck->route_count;
      
      $truck_average_stop_count_per_trip_sds = 0.0;
      $truck_average_trip_distance_sds = 0.0;
      $truck_average_stem_distance_sds = 0.0;
      $truck_average_speed_sds = 0.0;
      
      foreach( $truck->routes as $route )
      {
        $company_average_stop_count_per_trip_sds = $company_average_stop_count_per_trip_sds + pow($route->short_stops_count - $company->average_stop_count_per_trip, 2);
        $company_average_trip_distance_sds =  $company_average_trip_distance_sds + pow($route->distance - $company->average_trip_distance, 2);
        $company_average_stem_distance_sds = $company_average_stem_distance_sds + pow(($route->first_stem_distance + $route->second_stem_distance) - $company->average_stem_distance, 2);
        $company_average_speed_sds = $company_average_speed_sds + pow($route->average_speed - $company->average_speed, 2);
      
        $truck_average_stop_count_per_trip_sds = $truck_average_stop_count_per_trip_sds + pow($route->short_stops_count - $truck->average_stop_count_per_trip, 2);
        $truck_average_trip_distance_sds =  $truck_average_trip_distance_sds + pow($route->distance - $truck->average_trip_distance, 2);
        $truck_average_stem_distance_sds = $truck_average_stem_distance_sds + pow(($route->first_stem_distance + $route->second_stem_distance) - $truck->average_stem_distance, 2);
        $truck_average_speed_sds = $truck_average_speed_sds + pow($route->average_speed - $truck->average_speed, 2);
      }
      
      $truck_average_stop_count_per_trip_sd = 0.0;
      $truck_average_trip_distance_sd = 0.0;
      $truck_average_stem_distance_sd = 0.0;
      $truck_average_speed_sd = 0.0;
      
      if($truck_trip_count > 0)
      {
        $truck_average_stop_count_per_trip_sd = sqrt($truck_average_stop_count_per_trip_sds / $truck_trip_count);
        $truck_average_trip_distance_sd = sqrt($truck_average_trip_distance_sds / $truck_trip_count);
        $truck_average_stem_distance_sd = sqrt($truck_average_stem_distance_sds / $truck_trip_count);
        $truck_average_speed_sd = sqrt($truck_average_speed_sds / $truck_trip_count);  
      }
      
      $truck->average_stop_count_per_trip_sd = $truck_average_stop_count_per_trip_sd;
      $truck->average_trip_distance_sd = $truck_average_trip_distance_sd;
      $truck->average_stem_distance_sd = $truck_average_stem_distance_sd;
      $truck->average_speed_sd = $truck_average_speed_sd;
      $truck->save();
    }
    
    $company_average_stop_count_per_trip_sd = 0.0;
    $company_average_trip_distance_sd = 0.0;
    $company_average_stem_distance_sd = 0.0;
    $company_average_speed_sd = 0.0;
    
    if($company_trip_count > 0)
    {
      $company_average_stop_count_per_trip_sd = sqrt($company_average_stop_count_per_trip_sds / $company_trip_count);
      $company_average_trip_distance_sd = sqrt($company_average_trip_distance_sds / $company_trip_count);
      $company_average_stem_distance_sd = sqrt($company_average_stem_distance_sds / $company_trip_count);
      $company_average_speed_sd = sqrt($company_average_speed_sds / $company_trip_count);  
    }
    
    $company->average_stop_count_per_trip_sd = $company_average_stop_count_per_trip_sd;
    $company->average_trip_distance_sd = $company_average_trip_distance_sd;
    $company->average_stem_distance_sd = $company_average_stem_distance_sd;
    $company->average_speed_sd = $company_average_speed_sd;
    $company->save();
  }
  
  
  function actionGenerateCompanyMetrics()
  {
    $company = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
    $truck_count = count($company->trucks);
    $route_count = 0;
    $distance_traveled = 0.0;
    $total_short_stop_time = 0.0;
    $total_traveling_time  =0.0;
    $total_resting_time  =0.0;
    $short_stop_count = 0;
    $total_average_stem_distance = 0.0;
    $sum_average_trip_stop_time = 0.0;
    
    foreach( $company->trucks as $truck )
    {
      $route_count = $route_count + $truck->routesCount;
      $distance_traveled = $distance_traveled + $truck->total_distance;
      $total_short_stop_time = $total_short_stop_time + $truck->short_stops_time;
      $total_traveling_time = $total_traveling_time + $truck->traveling_time;
      $total_resting_time = $total_resting_time + $truck->resting_time;
      $total_average_stem_distance = $total_average_stem_distance + $truck->average_stem_distance;
      $sum_average_trip_stop_time = $sum_average_trip_stop_time + $truck->short_stops_time;
      $short_stop_count = $short_stop_count + $truck->stops_between_0_5;
      $short_stop_count = $short_stop_count + $truck->stops_between_5_15;
      $short_stop_count = $short_stop_count + $truck->stops_between_15_30;
      $short_stop_count = $short_stop_count + $truck->stops_between_30_60;
      $short_stop_count = $short_stop_count + $truck->stops_between_60_120;
      $short_stop_count = $short_stop_count + $truck->stops_between_120_plus;
    }
   
    $average_stop_count_per_trip = 0.0;
    $average_trip_distance = 0.0;
    $average_stem_distance = 0.0;
    $average_trip_duration = 0.0;
    $average_trip_stop_time = 0.0;
    $average_trip_traveling_time = 0.0;
    
    if($route_count > 0)
    {
      $average_stop_count_per_trip = $short_stop_count / $route_count;
      $average_trip_distance = $distance_traveled / $route_count;
      $average_stem_distance = $total_average_stem_distance / $route_count;
      $average_trip_duration = ( $company->traveling_time + $company->short_stop_time ) / $route_count;
      $average_trip_stop_time = $sum_average_trip_stop_time / $route_count;
      $average_trip_traveling_time = $company->traveling_time / $route_count;
    }
    
    $company->route_count = $route_count;
    $company->distance_traveled = $distance_traveled;
    $company->short_stop_time = $total_short_stop_time;
    $company->traveling_time = $total_traveling_time;
    $company->resting_time = $total_resting_time;
    $company->average_stop_count_per_trip = $average_stop_count_per_trip;
    $company->average_trip_distance = $average_trip_distance;
    $company->average_stem_distance = $average_stem_distance;
    $company->average_trip_duration = $average_trip_duration;
    $company->average_trip_stop_time = $average_trip_stop_time;
    $total_traveling_time_hours = $total_traveling_time / 3600.0;
    if($total_traveling_time_hours > 0)
      $company->average_speed = $distance_traveled / $total_traveling_time_hours;
    if($short_stop_count > 0)
      $company->average_short_stop_duration = $total_short_stop_time/$short_stop_count;
    $company->save();
    $company = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
  }
  
  function getTruckAverageSpeed($truck)
  {
    $total_distance = 0.0;
    $total_time = 0.0;
    $average_speed = 0.0;
    
    foreach($truck->routes as $route)
    {
      $distance = 0.0;
      if(!empty($route->distance))
        $distance = $route->distance;
      $time = 0.0;
      if(!empty($route->time))
      {
        if(!empty($route->short_stops_time))
          $time = ($route->time - $route->short_stops_time) / 3600.0;
        else
          $time = ($route->time) / 3600.0;
      }
      $total_distance = $total_distance + $distance;
      $total_time = $total_time + $time;
    }
    if($time > 0)
      $average_speed = $distance/$time;
    return $average_speed;
  }
  
  function calculateTruckAverageTripStopTime($truck)
  {
    $average_trip_stop_time = 0.0;
    if($truck->route_count > 0)
      $average_trip_stop_time = $truck->short_stops_time / $truck->route_count;
    return $average_trip_stop_time;
  }
  
  function calculateTruckAverageTripTravelingTime($truck)
  {
    $average_trip_traveling_time = 0.0;
    if($truck->route_count > 0)
      $average_trip_traveling_time = $truck->traveling_time / $truck->route_count;
    return $average_trip_traveling_time;
  }
  
  function actionGenerateTruckMetrics()
  {
    $limit = 1;
    $offset = 0;
    $limit_string = strval($limit);
    $offset_string = strval($offset);
    $criteria = new CDbCriteria();
    $criteria->addCondition('company_id = '.Yii::app()->user->getState('current_company'));
    $criteria->with = array('routesCount', 'timeSum','averageSpeedSum', 'distanceSum', 'shortStopsCountSum');
    $criteria->limit = $limit_string;
    $criteria->offset = $offset_string;
    $criteria->order = "t.id ASC";
    $trucks = Truck::model()->findAll($criteria);
    while(count($trucks) > 0)
    {
      foreach($trucks as $truck)
      {
        $route_count = $truck->routesCount;
        $truck->route_count = $route_count;
        $truck->total_distance = $truck->distanceSum;
        $truck->average_duration = $truck->timeSum/$route_count;
        $truck->average_speed = $this->getTruckAverageSpeed($truck);
        $truck->average_stop_count_per_trip = $truck->shortStopsCountSum/$route_count;
        $truck->average_distance_between_short_stops = $this->calculateAverageDistanceBetweenShortStops($truck);
        $truck->average_stem_distance = $this->calculateAverageStemDistance($truck);
        $truck->average_trip_distance = $this->calculateAverageTripDistance($truck);
        $truck->short_stops_time = $this->calculateShortStopsTime($truck);
        $truck->traveling_time = $this->calculateTravelingTime($truck);
        $truck->resting_time = $this->calculateTruckRestingTime($truck);
        $truck->average_trip_stop_time = $this->calculateTruckAverageTripStopTime($truck);
        $truck->average_trip_traveling_time = $this->calculateTruckAverageTripTravelingTime($truck);
        $this->generateTruckShortStopsRangesCount($truck);
        
        
        $truck->save();
      }
      $limit++;
      $offset++;
      $limit_string = strval($limit);
      $offset_string = strval($offset);
      $criteria->limit = $limit_string;
      $criteria->offset = $offset_string;
      $criteria->addCondition('company_id = '.Yii::app()->user->getState('current_company'));
      $trucks = Truck::model()->findAll($criteria);
    }
  }
  
  function generateTruckShortStopsRangesCount($truck)
  {
    $stops_between_0_5 = 0;
    $stops_between_5_15 = 0;
    $stops_between_15_30 = 0;
    $stops_between_30_60 = 0;
    $stops_between_60_120 = 0;
    $stops_between_120_plus = 0;
    $route_count = count($truck->routes);
    $resting_time = 0.0;
    
    if($route_count > 0)
      foreach($truck->routes as $route)
      {
        $stops_between_0_5 = $stops_between_0_5 + $route->stops_between_0_5;
        $stops_between_5_15 = $stops_between_5_15 + $route->stops_between_5_15;
        $stops_between_15_30 = $stops_between_15_30 + $route->stops_between_15_30;
        $stops_between_30_60 = $stops_between_30_60 + $route->stops_between_30_60;
        $stops_between_60_120 = $stops_between_60_120 + $route->stops_between_60_120;
        $stops_between_120_plus = $stops_between_120_plus + $route->stops_between_120_plus;
      }
    
    $truck->stops_between_0_5 = $stops_between_0_5;
    $truck->stops_between_5_15 = $stops_between_5_15;
    $truck->stops_between_15_30 = $stops_between_15_30;
    $truck->stops_between_30_60 = $stops_between_30_60;
    $truck->stops_between_60_120 = $stops_between_60_120;
    $truck->stops_between_120_plus = $stops_between_120_plus;
    $truck->save();
  }
  
  function calculateTruckRestingTime($truck)
  {
    $route_count = count($truck->routes);
    $resting_time = 0.0;
    
    if($route_count > 0)
    {
      if($truck->routes[0]->beginning_stop != null)
        $resting_time = $resting_time + $truck->routes[0]->beginning_stop->duration;
      foreach($truck->routes as $route)
      {
        if($truck->routes[0]->end_stop != null)
          $resting_time = $resting_time + $truck->routes[0]->end_stop->duration;
      }
      return $resting_time;
    }
    else
      return null;
  }
  
  function calculateTravelingTime($truck)
  {
    $route_count = count($truck->routes);
    $traveling_time = 0.0;
    
    if($route_count > 0)
    {
      foreach($truck->routes as $route)
        $traveling_time = $traveling_time + $route->traveling_time;
      return $traveling_time;
    }
    else
      return null;
  }
  
  function calculateShortStopsTime($truck)
  {
    $route_count = count($truck->routes);
    $short_stops_time = 0.0;
    
    if($route_count > 0)
    {
      foreach($truck->routes as $route)
        foreach($route->shortStops as $shortStop)
          $short_stops_time = $short_stops_time + $shortStop->duration;
      return $short_stops_time;
    }
    else
      return null;
  }
  
  function calculateAverageTripDistance($truck)
  {
    $route_count = count($truck->routes);
    $route_distance_sum = 0.0;
    
    if($route_count > 0)
    {
      foreach($truck->routes as $route)
        $route_distance_sum = $route_distance_sum + $route->distance;
      return $route_distance_sum/$route_count;
    }
    else
      return null;
  }
  
  public function calculateAverageStemDistance($truck)
  {
    $distanceSum  = 0.0;
    $count = 0;
    
    foreach($truck->routes as $route)
    {
      $distanceSum = $distanceSum + $route->first_stem_distance + $route->second_stem_distance;
      $count++;
    }
    $average;
    if($count > 0)
      $average = $distanceSum / $count;
    else
      $average = 0;
    return $average;
  }
  
  public function calculateAverageDistanceBetweenShortStops($truck)
  {
    $distanceSum  = 0.0;
    $count = 0;
    foreach($truck->routes as $route)
      if($route->distanceToNextShortStopSum != null)
      {
        $distanceSum = $distanceSum + $route->distanceToNextShortStopSum;
        $count = $count + $route->distanceToNextShortStopCount;
      }
    
    $average;
    if($count > 0)
      $average = $distanceSum / $count;
    else
      $average = 0;
    return $average;
  }
  
	public function actionUploadOne()
	{
	  $company_id = Yii::app()->user->getState('current_company');
	  $company_model = Company::model()->findByPk($company_id);
	  if($company_model->has_file_in_process != 1)
	  {
	    if ( empty($_FILES) || $_FILES["file"]["error"] ) {
      }
      else
      {
        $fileName = date('YmdHis').strval(rand()%10);
        move_uploaded_file($_FILES["file"]["tmp_name"], "../files/$fileName");
        $uploaded_file_model = new UploadedFile;
        $uploaded_file_model->company_id = $company_id;
        $uploaded_file_model->filename = $fileName;
        $uploaded_file_model->step = 0;
        $uploaded_file_model->save();
        $company_model->has_file_in_process = 1;
        $company_model->save();
        $uploaded_file_model->step = 1;
        $uploaded_file_model->save();
		  }
		}
	  else
	    $this->render('file_in_process',array());
  }
  
  function calculateDistance($lon1, $lat1, $lon2, $lat2)
  {
    //Parameters
    $km_per_deg_la = 111.3237;
    $km_per_deg_lo = 111.1350;
    $pi = 3.14159;
    
    if( ( abs($lat1) > 90 ) || ( abs($lat2) >90 ) || ( abs($lon1) > 360 ) || ( abs($lon2) >360 ) )
      return 0.0;
    if( $lon1 < 0 )
      $lon1 = $lon1 + 360;
    if( $lon2 < 0 )
      $lon2 = $lon2 + 360;
    $km_la = $km_per_deg_la * ($lat1-$lat2);
    if( abs($lon1-$lon2) > 180)
      $dif_lo = abs($lon1-$lon2)-180;
    else
      $dif_lo = abs($lon1-$lon2);
    $km_lo = $km_per_deg_lo * $dif_lo * cos(($lat1+$lat2)*$pi/360);
    $dist = sqrt(pow($km_la,2) + pow($km_lo,2));
    return $dist;
  }
  
  function calculateSpeedAndTime($firstSample, $secondSample)
  {
    $firstDate = new DateTime($firstSample->datetime);
    $secondDate = new DateTime($secondSample->datetime);
    $time_diff = $secondDate->getTimestamp()-$firstDate->getTimestamp();
    $secondSample->interval = $time_diff;
    //Speed is km/hr
    $aux = $secondSample->interval/3600.0;
    if($aux > 0)
      $secondSample->speed = $secondSample->distance / $aux;
    else
      $secondSample->speed = 0;
    $secondSample->save();
  }
  
  function calculateIfContinuous($firstSample, $secondSample)
  {
    $time_treshold = 21600;//6 hours
    
    $firstDate = new DateTime($firstSample->datetime);
    $secondDate = new DateTime($secondSample->datetime);
    $time_diff = $secondDate->getTimestamp()-$firstDate->getTimestamp();
    if($time_diff > $time_treshold)
      return false;
    else
      return true;
  }
  
  function saveDistance($sample, $distance)
  {
    $sample->distance = $distance;
    $sample->save();
  }
  
  function calculateDistanceSpeedAndTime($firstSample, $secondSample)
  {
    $lon1 = $firstSample->longitude;
    $lat1 = $firstSample->latitude;
    $lon2 = $secondSample->longitude;
    $lat2 = $secondSample->latitude;
    
    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
    $secondSample->distance = $distance;
    
    $secondSample->interval = $secondSample->datetime - $firstSample->datetime;
    $aux = $secondSample->interval/3600.0;
    if($aux > 0)
      $secondSample->speed = $secondSample->distance/$aux;
    else
      $secondSample->speed = 0;
    $secondSample->save();
  }
  
  function generateRouteDistance($route)
  {
    $total_distance = 0.0;
    foreach($route->samples as $sample)
      $total_distance = $total_distance + $sample->distance;
    $route->distance = $total_distance;
    $route->save();
  }
  
  function generateRouteAverageSpeed($route)
  {
    $average_speed = 0.0;
    $distance = 0.0;
    if(!empty($route->distance))
      $distance = $route->distance;
    $time = 0.0;
    if(!empty($route->time))
    {
      if(!empty($route->short_stops_time))
        $time = ($route->time - $route->short_stops_time)/3600.0;
      else
        $time = ($route->time)/3600.0;
    }
    if($time > 0)
      $average_speed = $distance/$time;
    $route->average_speed = $average_speed;
    $route->save();
  }
  
  function generateRouteStopsCount($route)
  {
    $short_stops_count = count($route->shortStops);
    $route->short_stops_count = $short_stops_count;
    $route->save();
  }
  
  function generateRouteTotalTime($route)
  {
    $samples_count = count($route->samples);
    $time_diff = 0;
    if($samples_count > 2)
    {
      $firstDate;
      $secondDate;
      if(!empty($route->beginning_stop))
        $firstDate = new DateTime($route->beginning_stop->end_time);
      else
        $firstDate = new DateTime($route->samples[0]->datetime);
      if(!empty($route->end_stop))
        $secondDate = new DateTime($route->end_stop->start_time);
      else
        $secondDate = new DateTime($route->samples[$samples_count - 1]->datetime);
      $time_diff = $secondDate->getTimestamp()-$firstDate->getTimestamp();
    }
    $route->time = $time_diff;
    $route->save();
  }
  
  function actionGenerateRouteMetrics()
  {
    $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
    $trucks_ids = array();
    foreach($trucks as $truck)
      $trucks_ids[] = $truck->id;
    $limit = 1;
    $offset = 0;
    $limit_string = strval($limit);
    $offset_string = strval($offset);
    $criteria = new CDbCriteria();
    $criteria->with = array('samples','shortStops','beginning_stop','end_stop');
    $criteria->limit = $limit_string;
    $criteria->offset = $offset_string;
    $criteria->order = "t.id ASC";
    $criteria->addInCondition('truck_id', $trucks_ids);
    $routes = Route::model()->findAll($criteria);
    while(count($routes) > 0)
    {
      foreach($routes as $route)
      {
        $this->generateRouteDistance($route);
        $this->generateRouteTotalTime($route);
        $this->generateRouteIsValid($route);
        if($route->is_valid == true)
        {
          $this->generateRouteStopsCount($route);
          $this->generateRouteShortStopsDistance($route);
          $this->generateRouteStemTimeAndDistance($route);
          $this->generateRouteShortStopsTime($route);
          $this->generateRouteAverageSpeed($route);
          $this->generateTravelingTime($route);
          $this->generateStopsRanges($route);
          $this->generateAverageStopDuration($route);
          $this->generateLongStopsDuration($route);
        }
        else//Delete all routes and samples that are not valid
        {
          foreach($route->samples as $sample)
            $sample->delete();
          $route->delete();
        }
      }
      $limit++;
      $offset++;
      $limit_string = strval($limit);
      $offset_string = strval($offset);
      $criteria->limit = $limit_string;
      $criteria->offset = $offset_string;
      $routes = Route::model()->findAll($criteria);
    }
  }
  
  function generateRouteIsValid($route)
  {
    $minimum_route_time = 1800;//30 minutes
    if($route->time < $minimum_route_time)
      $route->is_valid = false;
    else
      $route->is_valid = true;
    $route->save();
  }
  
  function generateLongStopsDuration($route)
  {
    if($route->beginning_stop != null)
      if($route->beginning_stop->duration == null)
      {
        $start_date = new DateTime($route->beginning_stop->start_time);
        $end_date = new DateTime($route->beginning_stop->end_time);
        $time_diff = $end_date->getTimestamp() - $start_date->getTimestamp();
        $route->beginning_stop->duration = $time_diff;
        $route->beginning_stop->save();
      }
    
    if($route->end_stop != null)
      if($route->end_stop->duration == null)
      {
        $start_date = new DateTime($route->end_stop->start_time);
        $end_date = new DateTime($route->end_stop->end_time);
        $time_diff = $end_date->getTimestamp() - $start_date->getTimestamp();
        $route->end_stop->duration = $time_diff;
        $route->end_stop->save();
      }
  }
  
  function generateAverageStopDuration($route)
  {
    $stop_count = count($route->shortStops);
    
    if($stop_count > 0)
    {
      $stop_duration_sum = 0.0;
      
      for($i = 0; $i < $stop_count; $i++)
        $stop_duration_sum = $stop_duration_sum + $route->shortStops[$i]->duration;
      
      $route->average_short_stop_duration = $stop_duration_sum/$stop_count;
      $route->save();
    }
    
  }
  
  //Uses five more seconds
  function generateStopsRanges($route)
  {
    $stops_between_0_5 = 0;
    $stops_between_5_15 = 0;
    $stops_between_15_30 = 0;
    $stops_between_30_60 = 0;
    $stops_between_60_120 = 0;
    $stops_between_120_plus = 0;
    
    for($i = 0; $i < count($route->shortStops); $i++)
    {
      $short_stop_duration = $route->shortStops[$i]->duration;
      if($short_stop_duration < 305)//5
      {
        $stops_between_0_5++;
      }
      else if($short_stop_duration < 905)//15
      {
        $stops_between_5_15++;
      }
      else if($short_stop_duration < 1805)//30
      {
        $stops_between_15_30++;
      }
      else if($short_stop_duration < 3605)//60
      {
        $stops_between_30_60++;
      }
      else if($short_stop_duration < 7205)//120
      {
        $stops_between_60_120++;
      }
      else //120+
      {
        $stops_between_120_plus++;
      }
    }
    
    $route->stops_between_0_5 = $stops_between_0_5;
    $route->stops_between_5_15 = $stops_between_5_15;
    $route->stops_between_15_30 = $stops_between_15_30;
    $route->stops_between_30_60 = $stops_between_30_60;
    $route->stops_between_60_120 = $stops_between_60_120;
    $route->stops_between_120_plus = $stops_between_120_plus;
    
    $route->save();
    
  }
  
  function generateTravelingTime($route)
  {
    
    $route->traveling_time = $route->time - $route->short_stops_time;
    $route->save();
  }
  
  function generateRouteShortStopsTime($route)
  {
    $total_time = 0.0;
    for($i = 0; $i < count($route->shortStops); $i++)
    {
      $short_stop = $route->shortStops[$i];
      $start_date = new DateTime($short_stop->start_time);
      $end_date = new DateTime($short_stop->end_time);
      $time_diff = $end_date->getTimestamp() - $start_date->getTimestamp();
      $short_stop->duration = $time_diff;
      $short_stop->save();
      
      $total_time = $total_time + $time_diff;
    }
    $route->short_stops_time = $total_time;
    $route->save();
  }
  
  function generateRouteStemTimeAndDistance($route)
  {
    $first_stem_distance = null;
    $second_stem_distance = null;
    $first_stem_time = null;
    $second_stem_time = null;
    $first_stem_stop = $route->getFirstStemStop();
    $last_stem_stop = $route->getLastStemStop();
    
    if(($first_stem_stop != null) && isset($route->beginning_stop))
    {
      $first_stem_start_time = $route->beginning_stop->end_time;
      $first_stem_start_lat =  $route->beginning_stop->latitude;
      $first_stem_start_lon = $route->beginning_stop->longitude;
      $first_stem_end_time = $first_stem_stop->start_time;
      $first_stem_end_lat = $first_stem_stop->latitude;
      $first_stem_end_lon = $first_stem_stop->longitude;
      
      $first_stem_distance = $this->calculateDistance($first_stem_start_lon, $first_stem_start_lat, $first_stem_end_lon, $first_stem_end_lat);
      
      $first_stem_start_date = new DateTime($first_stem_start_time);
      $first_stem_end_date = new DateTime($first_stem_end_time);
      $first_stem_time = $first_stem_end_date->getTimestamp() - $first_stem_start_date->getTimestamp();
    }
    
    if(($last_stem_stop != null) && isset($route->end_stop))
    {
      $second_stem_start_time = $last_stem_stop->end_time;
      $second_stem_start_lat = $last_stem_stop->latitude;
      $second_stem_start_lon = $last_stem_stop->longitude;
      $second_stem_end_time = $route->end_stop->start_time;
      $second_stem_end_lat = $route->end_stop->latitude;
      $second_stem_end_lon = $route->end_stop->longitude;
      
      $second_stem_distance = $this->calculateDistance($second_stem_start_lon, $second_stem_start_lat, $second_stem_end_lon, $second_stem_end_lat);
          
      $second_stem_start_date = new DateTime($second_stem_start_time);
      $second_stem_end_date = new DateTime($second_stem_end_time);
      $second_stem_time = $second_stem_end_date->getTimestamp() - $second_stem_start_date->getTimestamp();
    }
    
    $route->first_stem_distance = $first_stem_distance;
    $route->second_stem_distance = $second_stem_distance;
    $route->first_stem_time = $first_stem_time;
    $route->second_stem_time = $second_stem_time;
    $route->save();
  }
  
  function generateRouteShortStopsDistance($route)
  {
    $i = 0;
    $short_stops_count = count($route->shortStops);
    if($short_stops_count > 2)
    {
      while( $i < ($short_stops_count-1) )
      {
        $short_stop = $route->shortStops[$i];
        $next_stop = $route->shortStops[$i+1];
        $lon1 = $short_stop->longitude;
        $lat1 = $short_stop->latitude;
        $lon2 = $next_stop->longitude;
        $lat2 = $next_stop->latitude;
        $distance_to_next_stop = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
        $short_stop->distance_to_next_stop = $distance_to_next_stop;
        $short_stop->save();
        $i++;
      }
    }  
  }
  
  function actionFindSamplings()
  {
    $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
    foreach($trucks as $truck)
    {    
      $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
      $criteria->addCondition('truck_id = '.$truck->id);
      $samples = Sample::model()->findAll($criteria);
      $samples_size = count($samples);
      $sampling_name = 0;
      
      if($samples_size > 2)
      {
        $new_sampling = new Sampling;
        $new_sampling->truck_id = $truck->id;
        $sampling_name++;
        $new_sampling->name = $sampling_name;
        $new_sampling->save();
        $samples[0]->sampling_id = $new_sampling->id;
        $samples[0]->save();
        
        for($i = 1; $i < $samples_size; $i++)//Iterate through all the samples
        {
          if(!$this->calculateIfContinuous($samples[$i-1],$samples[$i]))
          {
            $new_samping = new Sampling;
            $new_sampling->truck_id = $truck->id;
            $sampling_name++;
            $new_sampling->name = $sampling_name;
            $new_sampling->save();
          }
          $samples[$i]->sampling_id = $new_sampling->id;
          $samples[$i]->save();
        }
      }
    }
  }
  
  function actionFindStopsAndRoutes()
  {
    $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
    foreach($trucks as $truck)
    {
      $samplings = $truck->samplings;
      foreach($samplings as $sampling)
      {    
        $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
        $criteria->addCondition('truck_id = '.$truck->id);
        $criteria->addCondition('sampling_id = '.$sampling->id);
        $samples = Sample::model()->findAll($criteria);
        
        $distance_treshold_for_short_stop= Company::model()->findByPk(Yii::app()->user->getState('current_company'))->distance_radius_short_stop;
        $time_treshold_for_short_stop= Company::model()->findByPk(Yii::app()->user->getState('current_company'))->time_radius_short_stop;
        $distance_treshold_for_long_stop= Company::model()->findByPk(Yii::app()->user->getState('current_company'))->distance_radius_long_stop;
        $time_treshold_for_long_stop= Company::model()->findByPk(Yii::app()->user->getState('current_company'))->time_radius_long_stop;
        
        $samples_size = count($samples);
        
        if($samples_size > 1)
        {
          $new_stop = null;// = new LongStop;
          //$new_stop->latitude = 0.0;
          //$new_stop->longitude = 0.0;
          //$new_stop->save();
          
          $previous_sample = $samples[0];
          $route_count = 0;
          $current_route = null;// = new Route;
          //$current_route->truck_id = $truck->id;
          //$current_route->name = $route_count;
          //$current_route->beginning_stop_id = $new_stop->id;
          //$current_route->save();
          //$samples[0]->route_id=$current_route->id;//Set the first coordinate to the first route
          //$samples[0]->save();
          $stop_start;
          $stop_end;
          $stop_type = 0;
          
          for($i = 1; $i < $samples_size; $i++)//Iterate through all the samples
          {
            $this->calculateDistanceSpeedAndTime($samples[$i-1],$samples[$i]);
            $stop_type = 0;
            $lon1 = $previous_sample->longitude;
            $lat1 = $previous_sample->latitude;
            $lon2 = $samples[$i]->longitude;
            $lat2 = $samples[$i]->latitude;
            
            $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
            $this->saveDistance($samples[$i], $distance);
            $this->calculateSpeedAndTime($samples[$i-1],$samples[$i]);
                    
            $previous_sample_date = new DateTime($previous_sample->datetime);
            $sample_i_date = new DateTime($samples[$i]->datetime);
            
            $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
            if($distance<$distance_treshold_for_short_stop )//If it is staying in "the same" place
            {
              //A stop begins
              $stop_start = $i;
              $stop_type = -1;
              
              //Possible problem
                
              while( ($distance<$distance_treshold_for_short_stop ) && ( $i<($samples_size-1) ))//While it stays in "the same" place
              {
                //A stop begins
                //Move one step forward
                $i++;
                $this->calculateDistanceSpeedAndTime($samples[$i-1], $samples[$i]);
                $sample_i_date = new DateTime($samples[$i]->datetime);
                $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
                
                //Recalculate distance for new position
                $lon2 = $samples[$i]->longitude;
                $lat2 = $samples[$i]->latitude;
                $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                
                if($date_diff_timestamp > $time_treshold_for_long_stop)//It has enough time to be a long stop
                {
                  $stop_type = -2;
                  //A stop becomes long stop
                  while(( $distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//Continue forward until it moves
                  {
                    //Move one step forward
                    $i++;
                    $this->calculateDistanceSpeedAndTime($samples[$i-1], $samples[$i]);
                    //Recalculate distance for new position
                    $lon2 = $samples[$i]->longitude;
                    $lat2 = $samples[$i]->latitude;
                    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                  }
                } 
              }
              while( ($distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//While it stays in "the same" place
              {
                //A stop begins
                //Move one step forward
                $i++;
                $this->calculateDistanceSpeedAndTime($samples[$i-1], $samples[$i]);
                $sample_i_date = new DateTime($samples[$i]->datetime);
                $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
                
                //Recalculate distance for new position
                $lon2 = $samples[$i]->longitude;
                $lat2 = $samples[$i]->latitude;
                $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                
                if($date_diff_timestamp > $time_treshold_for_long_stop)//It has enough time to be a long stop
                {
                  $stop_type = -2;
                  //A stop becomes long stop
                  while(( $distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//Continue forward until it moves
                  {
                    //Move one step forward
                    $i++;
                    $this->calculateDistanceSpeedAndTime($samples[$i-1], $samples[$i]);
                    //Recalculate distance for new position
                    $lon2 = $samples[$i]->longitude;
                    $lat2 = $samples[$i]->latitude;
                    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                  }
                }             
              }
              $stop_end = $i-1;
              $new_stop = null;
              if($current_route != null)
              {
                switch ($stop_type) {
                  case -1://Short stop
                    if($date_diff_timestamp > $time_treshold_for_short_stop)//Greater than the minimun for short stop
                    {
                      $new_stop = new ShortStop;
                      $new_stop->route_id = $current_route->id;
                      for($j = $stop_start; $j <= $stop_end; $j++)
                      {
                        $samples[$j]->route_id=$current_route->id; 
                        $samples[$j]->update();
                      }
                    }
                    else//Here we add just the first and last to the route
                    {
                      $samples[$stop_start]->route_id=$current_route->id; 
                      $samples[$stop_start]->update();
                      $samples[$stop_end]->route_id=$current_route->id; 
                      $samples[$stop_end]->update();
                    }
                    break;
                  case -2://Long stop
                    $new_stop = new LongStop;
                    break;
                }
                if($new_stop != null)
                {
                  $new_stop->latitude = $samples[$stop_start]->latitude;
                  $new_stop->longitude = $samples[$stop_start]->longitude;
                  $new_stop->start_time = $samples[$stop_start]->datetime;
                  $new_stop->end_time = $samples[$stop_end]->datetime;
                  $new_stop->save();
                  $new_stop->validate();
                
                  $new_stop->save();
                }
                if($stop_type == -2)//If it was long stop
                {
                  $route_count++;
                  $current_route->end_stop_id = $new_stop->id;
                  $current_route->save();
                  $current_route = new Route;
                  $current_route->truck_id = $truck->id;
                  $current_route->name = $route_count;
                  $current_route->beginning_stop_id = $new_stop->id;
                  $current_route->save();
                  $samples[$i-1]->route_id = $current_route->id;
                  $samples[$i-1]->update();
                }
              }
            }
            if($distance<$distance_treshold_for_long_stop )//It can only be a long stop
            {
              //A stop begins
              $stop_start = $i;
              $stop_type = -1;
              
              //Possible problem
                
              while( ($distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//While it stays in "the same" place
              {
                //A stop begins
                //Move one step forward
                $i++;
                $this->calculateDistanceSpeedAndTime($samples[$i-1], $samples[$i]);
                $sample_i_date = new DateTime($samples[$i]->datetime);
                $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
                
                //Recalculate distance for new position
                $lon2 = $samples[$i]->longitude;
                $lat2 = $samples[$i]->latitude;
                $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                
                if($date_diff_timestamp > $time_treshold_for_long_stop)//It has enough time to be a full Stop
                {
                  $stop_type = -2;
                  //A stop becomes long stop
                  while(( $distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//Continue forward until it moves
                  {
                    //Move one step forward
                    $i++;
                    $this->calculateDistanceSpeedAndTime($samples[$i-1], $samples[$i]);
                    //Recalculate distance for new position
                    $lon2 = $samples[$i]->longitude;
                    $lat2 = $samples[$i]->latitude;
                    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                  }
                } 
              }
              $stop_end = $i-1;
              $new_stop = null;
              if($current_route != null)
              {
                switch ($stop_type) {
                  case -1://It wasn't a long stop
                    break;
                  case -2://Long stop
                    $new_stop = new LongStop;
                    break;
                }
                
                if($new_stop != null)
                {
                  $new_stop->latitude = $samples[$stop_start]->latitude;
                  $new_stop->longitude = $samples[$stop_start]->longitude;
                  $new_stop->start_time = $samples[$stop_start]->datetime;
                  $new_stop->end_time = $samples[$stop_end]->datetime;
                  $new_stop->save();
                  $new_stop->validate();
                  
                  $new_stop->save();
                }
                
                if($stop_type == -2)//If it was long stop
                {
                  $route_count++;
                  $current_route->end_stop_id = $new_stop->id;
                  $current_route->save();
                  $current_route = new Route;
                  $current_route->truck_id = $truck->id;
                  $current_route->name = $route_count;
                  $current_route->beginning_stop_id = $new_stop->id;
                  $current_route->save();
                  $samples[$i-1]->route_id = $current_route->id;
                  $samples[$i-1]->update();
                }
              }
            }
            elseif($current_route == null)
            {
              $route_count++;
              $current_route = new Route;
              $current_route->truck_id = $truck->id;
              $current_route->name = $route_count;
              $current_route->save();
              $samples[$i-1]->route_id = $current_route->id;
              $samples[$i-1]->save();
              $samples[$i]->route_id = $current_route->id;
              $samples[$i]->save();
              
            }
            
            //Save parts of the route
            if($current_route != null)
            {
              $samples[$i]->route_id = $current_route->id;
              $samples[$i]->update();
            }
            $previous_sample = $samples[$i];
          }
        }
      }
    }
  }
  
  public function actionCreatePartial()
  {
    $company_id = Yii::app()->user->getState('current_company');
	  $company_model = Company::model()->findByPk($company_id);
	  if($company_model != null)
	  {
      $parameter_model = $this->createNewParametersForm();
		
		  $script = isset($_GET['script']);
		  $data = array(
        'step'=>1,
        'parameter_model'=>$parameter_model,
        'script'=>$script,
      );
      if($script)
        echo $this->renderPartial('_ajaxContent', $data, true, true);
      else 
        $this->renderPartial('_ajaxContent', $data, true, true);
    }
  }
  
  public function actionRecalculateData()
  {
    if(isset($_POST['cid']) && isset($_POST['uid']))
    {
      $cid = (int)$_POST['cid'];
      Yii::app()->user->setState('current_company',$cid);
      $uid = (int)$_POST['uid'];
      Yii::app()->user->setState('user', $uid);
      $identity_model = Identity::model()->findByPk(Yii::app()->user->getState('user'));
      $company_id = Yii::app()->user->getState('current_company');
      $company_model = Company::model()->findByPk($company_id);
      if($company_model->has_file_in_process != 1)
      {
        $company_model->has_file_in_process = 1;
        $company_model->save();
        $this->calculateAllMetrics();
        $company_model = Company::model()->findByPk($company_id);
        $company_model->has_file_in_process = 0;
        $company_model->save();
      }
    }
  }
  
  public function actionProcessData()
  {
    if(isset($_POST['cid']) && isset($_POST['uid']))
    {
      $cid = (int)$_POST['cid'];
      Yii::app()->user->setState('current_company',$cid);
      $uid = (int)$_POST['uid'];
      Yii::app()->user->setState('user', $uid);
      //Parse CSV file
      $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
      $filename = $uploaded_file_model->filename;
      $handler = fopen("../files/".$filename,'r');
      $trucks_array = array();
      $samples = array();
      $new_sample;
      
      fgetcsv($handler, 0, ',');//Ignore headers
      //Read each row and create the corresponding sample
      //Requires columns in the next order truck_name, latitude, longitude, and datetime
      while($pointer = fgetcsv($handler, 0, ','))
      {
        if(array_key_exists(3, $pointer))//Validates the row has enough columns
        {
          $new_sample = new Sample;
          $new_sample->truck_name = $pointer[0];
          $trucks_array[$pointer[0]] = 1;
          $new_sample->latitude = $pointer[1];
          $new_sample->longitude = $pointer[2];
          $new_sample->datetime = $pointer[3];
          $new_sample->status_id = -3;
          $new_sample->save();
        }
      }
	    fclose($handler);
	    //Create each of the trucks mentioned in the samples if any doesn't exist.
	    foreach($trucks_array as $truck_name => $value)
      {
        $condition_string = "name = '" . $truck_name . "' AND company_id = ".Yii::app()->user->getState('current_company');
        $registered_truck = Truck::model()->find($condition_string);
        if(!count($registered_truck))
        {
          $new_truck = new Truck;
          $new_truck->company_id = Company::model()->findByPk(Yii::app()->user->getState('current_company'))->id;
          $new_truck->name = $truck_name;
          $new_truck->save();
        }
      }
      //Set all the truck_ids of the sample, even if they were already set.
      
      $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
      foreach($trucks as $truck)
      {
        $criteria = new CDbCriteria();
        $criteria->addCondition('t.truck_name=\''.$truck->name.'\'');
        $truck_samples = Sample::model()->findAll($criteria);
        foreach($truck_samples as $truck_sample)
        {
          $truck_sample->truck_id = $truck->id;
          $truck_sample->save();
        }
      }
      
      //STart process
      $identity_model = Identity::model()->findByPk(Yii::app()->user->getState('user'));
      $pending_upload = $identity_model->pendingUpload();
      $pending_upload->step++;
      $pending_upload->save();
      $company_id = Yii::app()->user->getState('current_company');
      $company_model = Company::model()->findByPk($company_id);
      $this->calculateAllMetrics();
      $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>$company_id));
      unlink("../files/".$uploaded_file_model->filename);
      $uploaded_file_model->delete();
      $company_model = Company::model()->findByPk($company_id);
      $company_model->has_file_in_process = 0;
      $company_model->save();
    }
  }
    
  public function actionSubmitParameters()
  { 
    $company_id = Yii::app()->user->getState('current_company');
    $company_model = Company::model()->findByPk($company_id);
    if($company_model->has_file_in_process == 1)//Si está en procesamiento
    {
      error_log("paso 1");
      $parameter_model = $this->createNewParametersForm();
      if(isset($_POST['ParameterForm']))
	    {
	    error_log("paso 2");
		    $parameter_model->attributes=$_POST['ParameterForm'];
		    if($parameter_model->validate())
		    {
		    error_log("paso 3");
		      $parameter_model->updateCompanyParameters();
		      $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
          $uploaded_file_model->step = 2;
          $uploaded_file_model->save();
          $action_url = Yii::app()->createAbsoluteUrl('sample/processData');
          //ERunActions::touchUrl($action_url,array("cid"=>Yii::app()->user->getState('current_company'), "uid"=>Yii::app()->user->getState('user')),null);//$postData=null,$contentType=null)
          
          
          //////////////
          $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
      $filename = $uploaded_file_model->filename;
      $handler = fopen("../files/".$filename,'r');
      $trucks_array = array();
      $samples = array();
      $new_sample;
          fgetcsv($handler, 0, ',');//Ignore headers
      //Read each row and create the corresponding sample
      //Requires columns in the next order truck_name, latitude, longitude, and datetime
      while($pointer = fgetcsv($handler, 0, ','))
      {
        if(array_key_exists(3, $pointer))//Validates the row has enough columns
        {
          
          $new_sample = new Sample;
          $new_sample->truck_name = $pointer[0];
          $trucks_array[$pointer[0]] = 1;
          $new_sample->latitude = $pointer[1];
          $new_sample->longitude = $pointer[2];
          $new_sample->datetime = $pointer[3];
          $new_sample->status_id = -3;
          $new_sample->save();
        }
      }
	    fclose($handler);
	    error_log("paso 3");
	    //Create each of the trucks mentioned in the samples if any doesn't exist.
	    foreach($trucks_array as $truck_name => $value)
      {
        $condition_string = "name = '" . $truck_name . "' AND company_id = ".Yii::app()->user->getState('current_company');
        $registered_truck = Truck::model()->find($condition_string);
        if(!count($registered_truck))
        {
          $new_truck = new Truck;
          $new_truck->company_id = Company::model()->findByPk(Yii::app()->user->getState('current_company'))->id;
          $new_truck->name = $truck_name;
          $new_truck->save();
        }
      }
      //Set all the truck_ids of the sample, even if they were already set.
      
      $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
      error_log("paso 5");
      foreach($trucks as $truck)
      {
        $criteria = new CDbCriteria();
        $criteria->addCondition('t.truck_name=\''.$truck->name.'\'');
        $truck_samples = Sample::model()->findAll($criteria);
        foreach($truck_samples as $truck_sample)
        {
          $truck_sample->truck_id = $truck->id;
          $truck_sample->save();
        }
      }
      error_log("paso 6");
      //STart process
      $identity_model = Identity::model()->findByPk(Yii::app()->user->getState('user'));
      $pending_upload = $identity_model->pendingUpload();
      $pending_upload->step++;
      $pending_upload->save();
      $company_id = Yii::app()->user->getState('current_company');
      $company_model = Company::model()->findByPk($company_id);
      $this->calculateAllMetrics();
      $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>$company_id));
      unlink("../files/".$uploaded_file_model->filename);
      $uploaded_file_model->delete();
      $company_model = Company::model()->findByPk($company_id);
      $company_model->has_file_in_process = 0;
      $company_model->save();
          error_log("termino");
          /////
          
          
          
          
          echo CJSON::encode(array(
            'status'=>'success'
          ));
		    }
		    else
		    {
		      $error = CActiveForm::validate($parameter_model);
          if($error!='[]')
            echo $error;
          Yii::app()->end();
		    }
	    }
	  }
  }
  
  public function actionSendParameters()
  {
    $company_id = Yii::app()->user->getState('current_company');
	  $company_model = Company::model()->findByPk($company_id);
	  if($company_model->has_file_in_process != 1)
	  {
      $parameter_model = $this->createNewParametersForm();
      if(isset($_POST['ParameterForm']))
		  {
			  $parameter_model->attributes=$_POST['ParameterForm'];
			  if($parameter_model->validate())
			  {
			    echo CJSON::encode(array(
            'status'=>'success'
          ));
				  $this->refresh();
			  }
			  else
			  {
			    $error = CActiveForm::validate($parameter_model);
          if($error!='[]')
              echo $error;
          Yii::app()->end();
			  }
		  }
		}
	  else
	  {
	    $this->render('file_in_process',array());
	  }
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

		if(isset($_POST['Sample']))
		{
			$model->attributes=$_POST['Sample'];
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
	
		$dataProvider=new CActiveDataProvider('Sample');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Sample('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Sample']))
			$model->attributes=$_GET['Sample'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sample the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Sample::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Sample $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sample-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
