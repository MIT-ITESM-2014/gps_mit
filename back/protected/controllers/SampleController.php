<?php

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'uploadOne', 'uploadTwo', 'createPartial','findStopsAndRoutes', 'generateRouteMetrics'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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
	  //Add script for uploading files
	  $baseUrl = Yii::app()->baseUrl;
	  $cs = Yii::app()->getClientScript();
	  $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload.full.min.js', CClientScript::POS_HEAD);
    $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload_init.js', CClientScript::POS_END);
		
		$uploaded_files = Identity::model()->find('id='.Yii::app()->user->getId())->pendingUpload();
		
		//If it is at the second step
		//print_r($uploaded_files[0]);
		
		if( isset($uploaded_files[0]) )
		{
		  if($uploaded_files[0]->step ==1)
		  {
		    $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload_init_second.js', CClientScript::POS_END);
		    $uploaded_file = $uploaded_files[0];
		    $this->render('create',array(
			    'step'=>$uploaded_file->step,
		    ));
		  }
		}
		else //if it is in the first step
		{
		  $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload_init_first.js', CClientScript::POS_END);
		  $this->render('create',array(
			  'step'=>0,
		  ));
		}
	}
	
	public function actionUploadOne()
	{
	  if (empty($_FILES) || $_FILES["file"]["error"]) {
    }
    else
    {
      //$fileName = $_FILES["file"]["name"];
      $fileName = date('YmdHis').strval(rand()%10);
      move_uploaded_file($_FILES["file"]["tmp_name"], "../files/$fileName");
      $uploaded_file_model = new UploadedFile;
      $uploaded_file_model->created_at = date('Y-m-d H:i:s.u');
      $uploaded_file_model->updated_at = date('Y-m-d H:i:s.u');
      $uploaded_file_model->identity_id =  Yii::app()->user->getId();
      $uploaded_file_model->truck_file = $fileName;
      $uploaded_file_model->step = 1;
      $uploaded_file_model->save();
      
      //Parse CSV file
      $handler = fopen("../files/".$fileName,'r');
      
      $trucks = array();
      fgetcsv($handler, 0, ',');//Ignore headers
      //Requires first column to be the id present in the second file
      //Second column must be the name to identify the truck
      while($pointer = fgetcsv($handler, 0, ','))
      {
        $truck_id = $pointer[0];
        $truck_name = $pointer[1];
        if(!array_key_exists($truck_id, $trucks))
        {
          $trucks[$truck_id] = $truck_name;
        }
      }
      
      //Saving each of the trucks from the truck_file
      foreach($trucks as $key => $value)
      {
        $new_truck = new Truck;
        $new_truck->identifier = $key;
        $new_truck->name = $value;
        $new_truck->uploaded_file_id = $uploaded_file_model->id;
        $new_truck->validate();
        $new_truck->created_at = date('Y-m-d H:i:s.u');
        $new_truck->updated_at =  date('Y-m-d H:i:s.u');
        $new_truck->save();
      }
  
      
		  fclose($handler);

		}
  }
  
  
  function calculateDistance($lon1, $lat1, $lon2, $lat2)
  {
    //Parameters
    $km_per_deg_la = 111.3237;
    $km_per_deg_lo = 111.1350;
    $pi = 3.14159;
    
    if( ( abs($lat1) > 90 ) || ( abs($lat2) >90 ) || ( abs($lon1) > 360 ) || ( abs($lon2) >360 ) )
    {
      //TODO Throw exception for invalid coordinates
    } 
      
    if( $lon1 < 0 )
    {
      $lon1 = $lon1 + 360;
    }
    
    if( $lon2 < 0 )
    {
      $lon2 = $lon2 + 360;
    }
    
    $km_la = $km_per_deg_la * ($lat1-$lat2);
    
    if( abs($lon1-$lon2) > 180)
    {
      $dif_lo = abs($lon1-$lon2)-180;
    }
    else
    {
      $dif_lo = abs($lon1-$lon2);
    }
    
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
    print_r("<br>");
    print_r($aux);
    print_r("<br>");
    if($aux > 0)
      $secondSample->speed = $secondSample->distance / $aux;
    else
      $secondSample->speed = 0;
    $secondSample->save();
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
    //Distance of just one step
    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
    $secondSample->distance = $distance;  
    //Time interval
    $firstDate = new DateTime($firstSample->datetime);
    $secondDate = new DateTime($secondSample->datetime);
    $time_diff = $secondDate->getTimestamp()-$firstDate->getTimestamp();
    $secondSample->interval = $time_diff;
    //Speed is km/hr
    $aux = $secondSample->interval/3600.0;
        
    $secondSample->interval = $secondSample->datetime - $firstSample->datetime;
    //Speed is km/hr
    $aux = $secondSample->interval/3600.0;
    print_r("<br>");
    print_r($secondSample->interval." *** ".$secondSample->datetime." *** ".$firstSample->datetime);
    print_r("<br>");
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
    {
      $total_distance = $total_distance + $sample->distance;
    }
    $route->distance = $total_distance;
    $route->save();
  }
  
  function generateRouteAverageSpeed($route)
  {
    $speed_count = 0.0;
    $samples_count = 0;
    foreach($route->samples as $sample)
    {
      $speed_count = $speed_count + ($sample->speed*$sample->distance);
      $samples_count++;
    }
    $average_speed = $speed_count/$route->distance;
    $route->average_speed = $average_speed;
    $route->save();
  }
  
  function generateRouteStopsCount($route)
  {
    $short_stops_count = count($route->shortStops);
    $route->short_stops_count = $short_stops_count;
    $route->save();
  }
  
  //TODO change to long_stop beginning and end time
  //TODO fix bug of null when only two samples as part of the route.
  function generateRouteTotalTime($route)
  {
    $samples_count = count($route->samples);
    if($samples_count > 2)
    {
      $firstDate = new DateTime($route->samples[0]->datetime);
      $secondDate = new DateTime($route->samples[$samples_count - 1]->datetime);
      $time_diff = $secondDate->getTimestamp()-$firstDate->getTimestamp();
      $route->time = $time_diff;
      $route->save();
    }
  }
  
  function actionGenerateRouteMetrics()
  {
    $limit = 1;
    $offset = 0;
    $limit_string = strval($limit);
    $offset_string = strval($offset);
    $criteria = new CDbCriteria();
    $criteria->with = array('samples','shortStops','beginning_stop','end_stop');
    $criteria->limit = $limit_string;
    $criteria->offset = $offset_string;
    $criteria->order = "t.id ASC";
    $routes = Route::model()->findAll($criteria);
    while(count($routes) > 0)
    {
      foreach($routes as $route)
      {
        
        $this->generateRouteDistance($route);
        $this->generateRouteAverageSpeed($route);
        $this->generateRouteStopsCount($route);
        $this->generateRouteTotalTime($route);
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
  
  function actionFindStopsAndRoutes()
  {
    $trucks = Truck::model()->findAll();
    foreach($trucks as $truck)
    {    
      //TODO Add the right filters here
      $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
      $criteria->addCondition('truck_id = '.$truck->id);
      //$criteria->addBetweenCondition('datetime', '2013-07-06', '2013-07-16');
      $samples = Sample::model()->findAll($criteria);
      
      $distance_treshold_for_stop= 0.1; //TODO define treshold
      $time_treshold_for_stop= 14400;//Time in seconds
      
      
      
      $samples_size = count($samples);
      
      if($samples_size > 1)
      {
        $new_stop = new LongStop;
        $new_stop->latitude = 0.0;
        $new_stop->longitude = 0.0;
        $new_stop->created_at = date('Y-m-d H:i:s.u');
        $new_stop->updated_at = date('Y-m-d H:i:s.u');
        $new_stop->save();
        
        $previous_sample = $samples[0];
        $route_count = 1;
        $current_route = new Route;
        $current_route->name = $route_count;
        $current_route->beginning_stop_id = $new_stop->id;
        $current_route->save();
        $samples[0]->route_id=$current_route->id;//Set the first coordinate to the first route
        $samples[0]->save();
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
          
          //Distance of just one step
          $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
          $this->saveDistance($samples[$i], $distance);
          $this->calculateSpeedAndTime($samples[$i-1],$samples[$i]);
          //$previous_sample->route_id = $route_count;
          //$previous_sample->save();
          
          $previous_sample_date = new DateTime($previous_sample->datetime);
          $sample_i_date = new DateTime($samples[$i]->datetime);
          
          $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
          if($distance<$distance_treshold_for_stop )//If it is staying in "the same" place
          {
            //A stop begins
            $stop_start = $i;
            $stop_type = -1;
            
            //Possible problem
              
            while( ($distance<$distance_treshold_for_stop ) && ( $i<($samples_size-1) ))//While it stays in "the same" place
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
              
              if($date_diff_timestamp > $time_treshold_for_stop)//It has enough time to be a full Stop
              {
                $stop_type = -2;
                //A stop becomes long stop
                while(( $distance<$distance_treshold_for_stop ) && ( $i<($samples_size-1) ))//Continue forward until it moves
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
            for($j = $stop_start; $j <= $stop_end; $j++)
            {
              $samples[$j]->route_id=$current_route->id; 
              $samples[$j]->update();
            }
            
            switch ($stop_type) {
              case -1://Short stop
                $new_stop = new ShortStop;
                $new_stop->route_id = $current_route->id;
                break;
              case -2://Long stop
                $new_stop = new LongStop;
                break;
            }
            $new_stop->latitude = $samples[$stop_start]->latitude;
            $new_stop->longitude = $samples[$stop_start]->longitude;
            $new_stop->created_at = date('Y-m-d H:i:s.u');
            $new_stop->updated_at = date('Y-m-d H:i:s.u');
            $new_stop->save();
            $new_stop->validate();
            
            
            $new_stop->save();
            if($stop_type == -2)//If it was long stop
            {
              $route_count++;
              $current_route->end_stop_id = $new_stop->id;
              $current_route->save();
              $current_route = new Route;
              $current_route->name = $route_count;
              $current_route->beginning_stop_id = $new_stop->id;
              $current_route->save();
              $samples[$i-1]->route_id = $current_route->id;
              $samples[$i-1]->update();
            }
          }
          
          //Save parts of the route
          $samples[$i]->route_id = $current_route->id;
          $samples[$i]->update();
          
          $previous_sample  =$samples[$i];
        }
        $new_stop = new LongStop;
        $new_stop->latitude = 0.0;
        $new_stop->longitude = 0.0;
        $new_stop->created_at = date('Y-m-d H:i:s.u');
        $new_stop->updated_at = date('Y-m-d H:i:s.u');
        $new_stop->save();
        $current_route->end_stop_id = $new_stop->id;
        $current_route->save();
      }
    }
  }
  
  public function actionUploadTwo()
	{
	  if (empty($_FILES) || $_FILES["file"]["error"]) {
    }
    else
    {
      $fileName = date('YmdHis').strval(rand()%10);
      move_uploaded_file($_FILES["file"]["tmp_name"], "../files/$fileName");
      $condition_string = 'identity_id=' . Yii::app()->user->getId();
      $uploaded_file_model = UploadedFile::model()->find($condition_string);//
      
      $uploaded_file_model->updated_at = date('Y-m-d H:i:s.u');
      $uploaded_file_model->filename = $fileName;
      $uploaded_file_model->step = 2;
      $uploaded_file_model->save();
      
      //Parse CSV file
      $handler = fopen("../files/".$fileName,'r');
      
      //Get all trucks that belong to the current uploaded file model
      $condition_string2 = 'uploaded_file_id='.$uploaded_file_model->id;
      $trucks = Truck::model()->findAll($condition_string2);
      
      $trucks_array = array();
      foreach($trucks as $tr)
      {
        $identifier = $tr->identifier;
        $trucks_array[$identifier] = $tr->id;
      }
      
      $samples = array();
      fgetcsv($handler, 0, ',');//Ignore headers
      //Requires columns in the next order truck_name, latitude, longitude, and timestamp
      $new_sample;
      while($pointer = fgetcsv($handler, 0, ','))
      {
        $new_sample = new Sample;
        //TODO: Validate the truck exists
        $new_sample->truck_id = $trucks_array[$pointer[0]];
        $new_sample->latitude = $pointer[1];
        $new_sample->longitude = $pointer[2];
        //TODO: Add speed
        $new_sample->datetime = $pointer[3];
        $new_sample->created_at = date('Y-m-d H:i:s.u');
        //TODO: Remove updated_at when not null is activated
        $new_sample->updated_at = date('Y-m-d H:i:s.u');
        $new_sample->route_id = -3;
        //TODO:Define behaviour when unable to save
        $new_sample->save();
        
      }
		  fclose($handler);
		  
		  
		}
  }
  
  public function actionCreatePartial()
  {
    
    
    $data = array(
      'step'=>2
    );
    $this->renderPartial('_ajaxContent', $data, false, true);
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
