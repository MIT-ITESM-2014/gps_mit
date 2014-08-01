<?php

class ProcessingCommand extends CConsoleCommand {

  public $current_company = null;//$this->current_company->id
  public $buffer_samples = null;
  public $buffer_size = 2000;
  public $buffer_first = null;
  public $buffer_last = null;
  public $buffer_sampling = null;

  public function run($args) {
    Yii::getLogger()->autoDump = true;
    $companies = Company::model()->findAllByAttributes(array('has_file_in_process'=>1));
    foreach($companies as $company)
    {
      Yii::trace("Checking company ".$company->name, "cron.run");
      //Parse CSV file
      $this->current_company = $company;
      $this->cleanCompanyData();
      
      $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>$this->current_company->id));
      if(!empty($uploaded_file_model))
      {
        if($uploaded_file_model->step == 2)//Has uploaded parameters
        {
          $filename = $uploaded_file_model->filename;
          $handler = fopen(dirname(__FILE__)."/../../../files/".$filename,'r');
          $trucks_array = array();
          $samples = array();
          $new_sample;
          
          fgetcsv($handler, 0, ',');//Ignore headers
          while($pointer = fgetcsv($handler, 0, ','))
          {
            if(array_key_exists(3, $pointer))//Validates the row has enough columns
            {
              $new_sample = new Sample;
              $new_sample->truck_name = $pointer[0];
              $new_sample->company_id = $this->current_company->id;
              $trucks_array[$pointer[0]] = 1;
              $new_sample->latitude = $pointer[1];
              $new_sample->longitude = $pointer[2];
              $new_sample->datetime = $pointer[3];
              $new_sample->status_id = -3;
              $new_sample->save();
              unset($new_sample);
            }
          }
          fclose($handler);
          //Create each of the trucks mentioned in the samples if any doesn't exist.
          foreach($trucks_array as $truck_name => $value)
          {
            $condition_string = "name = '" . $truck_name . "' AND company_id = ".$this->current_company->id;
            $registered_truck = Truck::model()->find($condition_string);
            if(!count($registered_truck))
            {
              $new_truck = new Truck;
              $new_truck->company_id = $this->current_company->id;
              $new_truck->name = $truck_name;
              $new_truck->save();
            }
          }
          //Set all the truck_ids of the sample, even if they were already set.
          
          $trucks = Truck::model()->findAllByAttributes(array('company_id'=>$this->current_company->id));
          foreach($trucks as $truck)
          {
            $limit = 100;
            $offset = 0;
            $limit_string = strval($limit);
            $offset_string = strval($offset);
            $criteria = new CDbCriteria();
            $criteria->addCondition('company_id = '.$this->current_company->id);
            $criteria->addCondition('t.truck_name=\''.$truck->name.'\'');
            $criteria->limit = $limit_string;
            $criteria->offset = $offset_string;
            $criteria->order = "t.datetime ASC";
            $truck_samples = Sample::model()->findAll($criteria);
            while(count($truck_samples) > 0)
            { 
              foreach($truck_samples as $truck_sample)
              {
                $truck_sample->truck_id = $truck->id;
                $truck_sample->save();
              }
              $offset = $offset + $limit;
              $offset_string = strval($offset);
              $criteria->offset = $offset_string;
              $truck_samples = Sample::model()->findAll($criteria);
            }
          }

          //STart process
          $uploaded_file_model->step++;
          $uploaded_file_model->save();
          $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>$this->current_company->id));
          unlink(dirname(__FILE__)."/../../../files/".$uploaded_file_model->filename);
          $uploaded_file_model->delete();
        }//if($uploaded_file_model->step == 2)//has parameters
      }//if(!empty($uploaded_file_model))
      
      $this->calculateAllMetrics();
      $company=Company::model()->findByPk($company->id);
      $company->has_file_in_process = 0;
      $company->save();
    }    
  }
  
  function cleanCompanyData()
  {
      $trucks = Truck::model()->findAllByAttributes(array('company_id'=>$this->current_company->id));
      $trucks_ids = array();
      foreach($trucks as $truck)
        $trucks_ids[] = $truck->id;
      
      $criteria_find_routes = new CDbCriteria();
      $criteria_find_routes->select = 'id';
      $criteria_find_routes->addInCondition('truck_id', $trucks_ids);
      $criteria_find_routes->with = 'beginning_stop';
      $criteria_find_routes->with = 'end_stop';
      
      $routes = Route::model()->findAll($criteria_find_routes);
      $routes_ids = array();
      $long_stops_ids = array();
      foreach($routes as $route)
      {
        $routes_ids[] = $route->id;
        if(!empty($route->beginning_stop))
          $long_stops_ids[] = $route->beginning_stop->id;
        if(!empty($route->end_stop))
          $long_stops_ids[] = $route->end_stop->id;
      }
      
      //Unlinking samples from sampling
      Yii::app()->db
      ->createCommand("UPDATE sample SET sampling_id = null, route_id = null WHERE company_id = ".$this->current_company->id)
      ->execute();
      //sampling
      $criteria_sampling = new CDbCriteria();
      $criteria_sampling->addInCondition('truck_id', $trucks_ids);
      Sampling::model()->deleteAll($criteria_sampling);
      //Shortstop
      $criteria_short_stop = new CDbCriteria();
      $criteria_short_stop->addInCondition('route_id', $routes_ids);
      ShortStop::model()->deleteAll($criteria_short_stop);
      //route
      $criteria_route = new CDbCriteria();
      $criteria_route->addInCondition('id', $routes_ids);
      Route::model()->deleteAll($criteria_route);
      //LongStop
      $criteria_long_stop = new CDbCriteria();
      $criteria_long_stop->addInCondition('id', $long_stops_ids);
      LongStop::model()->deleteAll($criteria_long_stop);
      
      
      $company = Company::model()->findByPk($this->current_company->id);
      $company->has_file_in_process = 0;
      $company->route_count = null;
      $company->average_speed = null;
      $company->average_stop_count_per_trip = null;
      $company->average_trip_distance = null;
      $company->average_stem_distance = null;
      $company->short_stop_time = null;
      $company->traveling_time = null;
      $company->resting_time = null;
      $company->distance_traveled = null;
      $company->average_short_stop_duration = null;
      $company->average_trip_duration = null;
      $company->average_trip_stop_time = null;
      $company->average_trip_traveling_time = null;
      $company->average_stop_count_per_trip_sd = null;
      $company->average_trip_distance_sd = null;
      $company->average_stem_distance_sd = null;
      $company->average_speed_sd = null;
      $company->average_trip_duration_sd = null;
      $company->average_trip_stop_time_sd = null;
      $company->average_trip_traveling_time_sd = null;
      $company->save();
  }
  
  function calculateAllMetrics()
  {
    error_log("actionFindSamplings");
    $this->actionFindSamplings();
    error_log("actionFindStopsAndRoutes");
    $this->actionFindStopsAndRoutes();
    error_log("actionGenerateRouteMetric");
    $this->actionGenerateRouteMetrics();
    error_log("actionGenerateTruckMetrics");
    $this->actionGenerateTruckMetrics();
    error_log("actionGenerateCompanyMetrics");
    $this->actionGenerateCompanyMetrics();
    error_log("actionGenerateStandardDeviation");
    $this->actionGenerateStandardDeviation();
    error_log("finished calculating metrics");
  }
  
  function actionFindSamplings()
  {
    $trucks = Truck::model()->findAllByAttributes(array('company_id'=>$this->current_company->id));
    foreach($trucks as $truck)
    {    
      $limit = 100;
      $offset = 0;
      $limit_string = strval($limit);
      $offset_string = strval($offset);
      $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
      $criteria->addCondition('truck_id = '.$truck->id);
      $criteria->limit = $limit_string;
      $criteria->offset = $offset_string;
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
        $last_sample = null;
        $current_sample = null;
        while($samples_size > 0)
        {
          for($i = 0; $i < $samples_size; $i++)//Iterate through all the samples
          {
            $current_sample = $samples[$i];
            if(($last_sample != null) && (!$this->calculateIfContinuous($last_sample,$current_sample)))
            {
              $new_sampling = new Sampling;
              $new_sampling->truck_id = $truck->id;
              $sampling_name++;
              $new_sampling->name = $sampling_name;
              $new_sampling->save();
            }
            $current_sample->sampling_id = $new_sampling->id;
            $current_sample->save();
            $last_sample = $current_sample;
          } //for($i = 1; $i < $samples_size; $i++)
          $offset = $offset + $limit;
          $offset_string = strval($offset);
          $criteria->offset = $offset_string;
          $samples = Sample::model()->findAll($criteria);
          $samples_size = count($samples);
        } //while($samples_size > 0)
      }
    }
  }//actionFindSamplings
  
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
  }//calculateIfContinuous
  
  function actionFindStopsAndRoutes()
  {
    $trucks = Truck::model()->findAllByAttributes(array('company_id'=>$this->current_company->id));
    foreach($trucks as $truck)
    {
      $samplings = $truck->samplings;
      foreach($samplings as $sampling)
      {    
        //$samples = $sampling->samples;
        $distance_treshold_for_short_stop= Company::model()->findByPk($this->current_company->id)->distance_radius_short_stop;
        $time_treshold_for_short_stop= Company::model()->findByPk($this->current_company->id)->time_radius_short_stop;
        $distance_treshold_for_long_stop= Company::model()->findByPk($this->current_company->id)->distance_radius_long_stop;
        $time_treshold_for_long_stop= Company::model()->findByPk($this->current_company->id)->time_radius_long_stop;
        
        $samples_size = $sampling->samplesCount;
        $sampling_id = $sampling->id;
        
        if($samples_size > 1)//Secure check
        {
          $new_stop = null;
          $previous_sample = $this->getSamplingSampleAt($sampling_id,0);
          $route_count = 0;
          $current_route = null;
          $stop_start;
          $stop_end;
          $stop_type = 0;
          
          for($i = 1; $i < $samples_size; $i++)//Iterate through all the samples
          {
            //$this->calculateDistanceSpeedAndTime($this->getSamplingSampleAt($sampling_id,$i-1),$this->getSamplingSampleAt($sampling_id,$i));//The function saves the results
            $stop_type = 0;//stop type yet not determined
            $lon1 = $previous_sample->longitude;
            $lat1 = $previous_sample->latitude;
            $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
            $lon2 = $sample_i->longitude;
            $lat2 = $sample_i->latitude;
            $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
            $sample_i->distance = $distance;
            $sample_i->save();
            $this->calculateSpeedAndTime($this->getSamplingSampleAt($sampling_id,$i-1),$sample_i);//The function saves the results
                    
            $previous_sample_date = new DateTime($previous_sample->datetime);
            $sample_i_date = new DateTime($sample_i->datetime);
            
            unset($sample_i);
            
            $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
            $stop_start = $i-1;
            if($distance<$distance_treshold_for_short_stop )//If it is staying in "the same" place
            {
              $stop_type = -1;
              while( ($distance<$distance_treshold_for_short_stop ) && ( $i<($samples_size-1) ))//While it stays in "the same" place for short stop
              {
                //Move one step forward
                $i++;
                $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
                $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                
                $this->calculateDistanceSpeedAndTime($sample_i_m1, $sample_i);
                $sample_i_date = new DateTime($sample_i->datetime);
                $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();//previous_sample_date is the start of the stop
                
                //Recalculate distance for new position
                $lon2 = $sample_i->longitude;
                $lat2 = $sample_i->latitude;
                $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                
                unset($sample_i);
                unset($sample_i_m1);
                
                if($date_diff_timestamp > $time_treshold_for_long_stop)//It has enough time to be a long stop
                {
                  $stop_type = -2;//It is long stop
                  while(( $distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//Continue forward until it moves
                  {
                    //Move one step forward
                    $i++;
                    $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
                    $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                    
                    $this->calculateDistanceSpeedAndTime($sample_i_m1, $sample_i);
                    //Recalculate distance for new position
                    $lon2 = $sample_i->longitude;
                    $lat2 = $sample_i->latitude;
                    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                    
                    unset($sample_i);
                    unset($sample_i_m1);
                  }
                } 
              }
              while( ($distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//While it stays in "the same" place only for long_stop
              {
                //A long stop could begin
                $i++;//Move one step forward
                $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
                $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                
                $this->calculateDistanceSpeedAndTime($sample_i_m1, $sample_i);
                $sample_i_date = new DateTime($sample_i->datetime);
                $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
                
                //Recalculate distance for new position
                $lon2 = $sample_i->longitude;
                $lat2 = $sample_i->latitude;
                $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                
                unset($sample_i);
                unset($sample_i_m1);
                    
                if($date_diff_timestamp > $time_treshold_for_long_stop)//It has enough time to be a long stop
                {
                  $stop_type = -2;
                  //A stop becomes long stop
                  while(( $distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//Continue forward until it moves
                  {
                    //Move one step forward
                    $i++;
                    $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
                    $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                    
                    $this->calculateDistanceSpeedAndTime($sample_i_m1, $sample_i);
                    //Recalculate distance for new position
                    $lon2 = $sample_i->longitude;
                    $lat2 = $sample_i->latitude;
                    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                    
                    unset($sample_i);
                    unset($sample_i_m1);
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
                        $sample_j = $this->getSamplingSampleAt($sampling_id,$j);
                        $sample_j->route_id=$current_route->id; 
                        $sample_j->update();
                        unset($sample_j);
                      }
                    }
                    else//Here we add just the first and last to the route
                    {
                      $sample_stop_start = $this->getSamplingSampleAt($sampling_id,$stop_start);
                      $sample_stop_start->route_id=$current_route->id; 
                      $sample_stop_start->update();
                      unset($sample_stop_start);
                      $sample_stop_end = $this->getSamplingSampleAt($sampling_id,$stop_end);
                      $sample_stop_end->route_id=$current_route->id; 
                      $sample_stop_end->update();
                      unset($sample_stop_end);
                    }
                    break;
                  case -2://Long stop
                    $new_stop = new LongStop;
                    break;
                }
                if($new_stop != null)
                {
                  $sample_stop_start = $this->getSamplingSampleAt($sampling_id,$stop_start);
                  $sample_stop_end = $this->getSamplingSampleAt($sampling_id,$stop_end);
                  $new_stop->latitude = $sample_stop_start->latitude;
                  $new_stop->longitude = $sample_stop_start->longitude;
                  $new_stop->start_time = $sample_stop_start->datetime;
                  $new_stop->end_time = $sample_stop_end->datetime;
                  $new_stop->save();
                  $new_stop->validate();
                
                  $new_stop->save();
                  unset($sample_stop_start);
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
                  $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                  $sample_i_m1->route_id = $current_route->id;
                  $sample_i_m1->update();
                  unset($sample_i_m1);
                }
              }
            }
            if($distance<$distance_treshold_for_long_stop )//It can only be a long stop
            {
              //A stop begins
              $stop_type = 0;
              
              while( ($distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//While it stays in "the same" place for long_stop but could not have yet enough duration
              {
                //A long stop could begin
                $i++;//Move one step forward
                $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
                $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                
                $this->calculateDistanceSpeedAndTime($sample_i_m1, $sample_i);
                $sample_i_date = new DateTime($sample_i->datetime);
                $date_diff_timestamp = $sample_i_date->getTimestamp() - $previous_sample_date->getTimestamp();
                
                //Recalculate distance for new position
                $lon2 = $sample_i->longitude;
                $lat2 = $sample_i->latitude;
                $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                
                unset($sample_i);
                unset($sample_i_m1);
                
                if($date_diff_timestamp > $time_treshold_for_long_stop)//It has enough time to be a full Stop
                {
                  $stop_type = -2;
                  //A stop becomes long stop
                  while(( $distance<$distance_treshold_for_long_stop ) && ( $i<($samples_size-1) ))//Continue forward until it moves
                  {
                    //Move one step forward
                    $i++;
                    $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
                    $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                    
                    $this->calculateDistanceSpeedAndTime($sample_i_m1, $sample_i);
                    //Recalculate distance for new position
                    $lon2 = $sample_i->longitude;
                    $lat2 = $sample_i->latitude;
                    $distance = $this->calculateDistance($lon1, $lat1, $lon2, $lat2);
                    
                    unset($sample_i);
                    unset($sample_i_m1);
                  }
                } 
              }
              $stop_end = $i-1;
              $new_stop = null;
              
              if(($current_route != null)&&($stop_type == -2))//If it was long stop
              {
                $new_stop = new LongStop;
                $sample_stop_start = $this->getSamplingSampleAt($sampling_id,$stop_start);
                $sample_stop_end = $this->getSamplingSampleAt($sampling_id,$stop_end);
                $new_stop->latitude = $sample_stop_start->latitude;
                $new_stop->longitude = $sample_stop_start->longitude;
                $new_stop->start_time = $sample_stop_start->datetime;
                $new_stop->end_time = $sample_stop_end->datetime;
                $new_stop->save();
                $new_stop->validate();
                $new_stop->save();
                unset($sample_stop_start);
                $route_count++;
                $current_route->end_stop_id = $new_stop->id;
                $current_route->save();
                $current_route = new Route;
                $current_route->truck_id = $truck->id;
                $current_route->name = $route_count;
                $current_route->beginning_stop_id = $new_stop->id;
                $current_route->save();
                $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
                $sample_i_m1->route_id = $current_route->id;
                $sample_i_m1->update();
                unset($sample_i_m1);
              }
            }
            elseif($current_route == null)//It is starting
            {
              $route_count++;
              $current_route = new Route;
              $current_route->truck_id = $truck->id;
              $current_route->name = $route_count;
              $current_route->save();
              $sample_i_m1 = $this->getSamplingSampleAt($sampling_id,$i-1);
              $sample_i_m1->route_id = $current_route->id;
              $sample_i_m1->save();
              unset($sample_i_m1);
              $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
              $sample_i->route_id = $current_route->id;
              $sample_i->save();
              unset($sample_i);
              
            }
            
            //Save parts of the route
            if($current_route != null)
            {
              $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
              $sample_i->route_id = $current_route->id;
              $sample_i->update();
              unset($sample_i);
            }
            $sample_i = $this->getSamplingSampleAt($sampling_id,$i);
            $previous_sample = $sample_i;
            unset($sample_i);
          }
        }
      }
    }
  }//actionFindStopsAndRoutes
  
  function getSamplingSampleAt($sampling_id, $offset)
  {
    if( ($offset < $this->buffer_first) || ($offset > $this->buffer_last) || ($this->buffer_sampling != $sampling_id) || ($offset == null))//If it is not available in buffer
    {
      $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
      $criteria->addCondition('sampling_id = '.$sampling_id);
      $criteria->limit = strval($this->buffer_size);
      $criteria_offset = (int)($offset/1000)  * 1000; 
      $criteria->offset = $criteria_offset;
      $this->buffer_samples = Sample::model()->findAll($criteria);
      $this->buffer_sampling = $sampling_id;
      $this->buffer_first = $criteria_offset;
      $this->buffer_last = $criteria_offset + $this->buffer_size - 1;
    }
    if(isset($this->buffer_samples[$offset - $this->buffer_first]))
      return $this->buffer_samples[$offset - $this->buffer_first];
    else
      return null;
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
  }//calculateSpeedAndTime
  
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
  }//calculateDistanceSpeedAndTime
  
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
  }//calculateDistance
  
  function actionGenerateRouteMetrics()
  {
    $trucks = Truck::model()->findAllByAttributes(array('company_id'=>$this->current_company->id));
    $trucks_ids = array();
    foreach($trucks as $truck)
      $trucks_ids[] = $truck->id;
    $limit = 1;
    $offset = 0;
    $limit_string = strval($limit);
    $offset_string = strval($offset);
    $criteria = new CDbCriteria();
    //$criteria->with = array('samples','shortStops','beginning_stop','end_stop');
    $criteria->limit = $limit_string;
    $criteria->offset = $offset_string;
    $criteria->order = "t.id ASC";
    $criteria->addInCondition('truck_id', $trucks_ids);
    $routes = Route::model()->findAll($criteria);
    while(count($routes) > 0)
    {
      foreach($routes as $route)
      {
        $route = $this->generateRouteDistance($route);
        $route = $this->generateRouteTotalTime($route);
        $route = $this->generateRouteIsValid($route);
        if($route->is_valid == true)
        {
          $route = $this->generateRouteStopsCount($route);
          $route = $this->generateRouteShortStopsDistance($route);
          $route = $this->generateRouteStemTimeAndDistance($route);
          $route = $this->generateRouteShortStopsTime($route);
          $route = $this->generateRouteAverageSpeed($route);
          $route = $this->generateTravelingTime($route);
          $route = $this->generateStopsRanges($route);
          $route = $this->generateAverageStopDuration($route);
          $route = $this->generateLongStopsDuration($route);
        }
        else//Delete all routes and samples that are not valid
        {
          foreach($route->shortStops as $short_stop)
            $short_stop->delete();
          foreach($route->samples as $sample)
            $sample->delete();
          $route->delete();
        }
      }
      $offset++;
      $limit_string = strval($limit);
      $offset_string = strval($offset);
      $criteria->limit = $limit_string;
      $criteria->offset = $offset_string;
      $routes = Route::model()->findAll($criteria);
    }
  }//actionGenerateRouteMetrics
  
  function generateRouteDistance($route)
  {
    $total_distance = 0.0;
    foreach($route->samples as $sample)
      $total_distance = $total_distance + $sample->distance;
    $route->distance = $total_distance;
    $route->save();
    return $route;
  }//generateRouteDistance
  
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
    return $route;
  }//generateRouteTotalTime
  
  function generateRouteIsValid($route)
  {
    $minimum_route_time = 1800;//30 minutes
    if($route->time < $minimum_route_time)
      $route->is_valid = false;
    else
      $route->is_valid = true;
    $route->save();
    return $route;
  }//generateRouteIsValid
  
  function generateRouteStopsCount($route)
  {
    $short_stops_count = count($route->shortStops);
    $route->short_stops_count = $short_stops_count;
    $route->save();
    return $route;
  }//generateRouteStopsCount
  
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
    return $route;  
  }//generateRouteShortStopsDistance
  
  function generateRouteStemTimeAndDistance($route)
  {
    $first_stem_distance = 0.0;
    $second_stem_distance = 0.0;
    $first_stem_time = 0.0;
    $second_stem_time = 0.0;
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
    return $route;
  }//generateRouteStemTimeAndDistance
  
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
    return $route;
  }//generateRouteShortStopsTime
  
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
    return $route;
  }//generateRouteAverageSpeed
  
  function generateTravelingTime($route)
  {
    $route->traveling_time = $route->time - $route->short_stops_time;
    $route->save();
    return $route;
  }//generateTravelingTime
  
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
        $stops_between_0_5++;
      else if($short_stop_duration < 905)//15
        $stops_between_5_15++;
      else if($short_stop_duration < 1805)//30
        $stops_between_15_30++;
      else if($short_stop_duration < 3605)//60
        $stops_between_30_60++;
      else if($short_stop_duration < 7205)//120
        $stops_between_60_120++;
      else //120+
        $stops_between_120_plus++;
    }
    
    $route->stops_between_0_5 = $stops_between_0_5;
    $route->stops_between_5_15 = $stops_between_5_15;
    $route->stops_between_15_30 = $stops_between_15_30;
    $route->stops_between_30_60 = $stops_between_30_60;
    $route->stops_between_60_120 = $stops_between_60_120;
    $route->stops_between_120_plus = $stops_between_120_plus;
    
    $route->save();
    return $route;
  }//generateStopsRanges
  
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
    return $route;
  }//generateAverageStopDuration
  
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
    return $route;
  }//generateLongStopsDuration
  
  function actionGenerateTruckMetrics()
  {
    $limit = 1;
    $offset = 0;
    $limit_string = strval($limit);
    $offset_string = strval($offset);
    $criteria = new CDbCriteria();
    $criteria->addCondition('company_id = '.$this->current_company->id);
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
      //$limit++;
      $offset = $limit + $offset;
      $limit_string = strval($limit);
      $offset_string = strval($offset);
      $criteria->limit = $limit_string;
      $criteria->offset = $offset_string;
      $criteria->addCondition('company_id = '.$this->current_company->id);
      $trucks = Truck::model()->findAll($criteria);
    }
  }//actionGenerateTruckMetrics
  
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
    if($total_time > 0)
      $average_speed = $total_distance/$total_time;
    return $average_speed;
  }//getTruckAverageSpeed
  
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
  }//calculateAverageDistanceBetweenShortStops
  
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
  }//calculateAverageStemDistance
  
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
  }//calculateAverageTripDistance
  
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
  }//calculateShortStopsTime
  
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
  }//calculateTravelingTime
  
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
  }//calculateTruckRestingTime
  
  function calculateTruckAverageTripStopTime($truck)
  {
    $average_trip_stop_time = 0.0;
    if($truck->route_count > 0)
      $average_trip_stop_time = $truck->short_stops_time / $truck->route_count;
    return $average_trip_stop_time;
  }//calculateTruckAverageTripStopTime
  
  function calculateTruckAverageTripTravelingTime($truck)
  {
    $average_trip_traveling_time = 0.0;
    if($truck->route_count > 0)
      $average_trip_traveling_time = $truck->traveling_time / $truck->route_count;
    return $average_trip_traveling_time;
  }//calculateTruckAverageTripTravelingTime
  
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
  }//generateTruckShortStopsRangesCount
  
  function actionGenerateCompanyMetrics()
  {
    $company = $this->current_company;
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
      $average_trip_duration = ( $total_traveling_time + $total_short_stop_time ) / $route_count;
      $average_trip_stop_time = $sum_average_trip_stop_time / $route_count;
      $average_trip_traveling_time = $total_traveling_time / $route_count;
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
    $company->average_trip_traveling_time = $average_trip_traveling_time;
    $total_traveling_time_hours = $total_traveling_time / 3600.0;
    $company->average_speed = 0.0;
    if($total_traveling_time_hours > 0)
      $company->average_speed = $distance_traveled / $total_traveling_time_hours;
    $company->average_short_stop_duration = 0.0;
    if($short_stop_count > 0)
      $company->average_short_stop_duration = $total_short_stop_time/$short_stop_count;
    $company->save();
  }//actionGenerateCompanyMetrics
  
  function actionGenerateStandardDeviation()
  {
    $company = Company::model()->findByPk($this->current_company->id);
    $company_trip_count = $company->route_count;
    
    $company_average_stop_count_per_trip_sds = 0.0;
    $company_average_trip_distance_sds = 0.0;
    $company_average_stem_distance_sds = 0.0;
    $company_average_speed_sds = 0.0;
    $company_average_trip_duration_sds = 0.0;
    $company_average_trip_stop_time_sds = 0.0;
    $company_average_trip_traveling_time_sds = 0.0;
    
    foreach( $company->trucks as $truck )
    {
      $truck_trip_count = $truck->route_count;
      
      $truck_average_stop_count_per_trip_sds = 0.0;
      $truck_average_trip_distance_sds = 0.0;
      $truck_average_stem_distance_sds = 0.0;
      $truck_average_speed_sds = 0.0;
      $truck_average_trip_duration_sds = 0.0;
      $truck_average_trip_stop_time_sds = 0.0;
      $truck_average_trip_traveling_time_sds = 0.0;
    
      
      foreach( $truck->routes as $route )
      {
        $company_average_stop_count_per_trip_sds = $company_average_stop_count_per_trip_sds + pow($route->short_stops_count - $company->average_stop_count_per_trip, 2);
        $company_average_trip_distance_sds =  $company_average_trip_distance_sds + pow($route->distance - $company->average_trip_distance, 2);
        $company_average_stem_distance_sds = $company_average_stem_distance_sds + pow(($route->first_stem_distance + $route->second_stem_distance) - $company->average_stem_distance, 2);
        $company_average_speed_sds = $company_average_speed_sds + pow($route->average_speed - $company->average_speed, 2);
        $company_average_trip_duration_sds = $company_average_trip_duration_sds + $route->time;
        $company_average_trip_stop_time_sds = $company_average_trip_stop_time_sds + $route->short_stops_time;
        $company_average_trip_traveling_time_sds = $company_average_trip_traveling_time_sds + $route->traveling_time;
      
        $truck_average_stop_count_per_trip_sds = $truck_average_stop_count_per_trip_sds + pow($route->short_stops_count - $truck->average_stop_count_per_trip, 2);
        $truck_average_trip_distance_sds =  $truck_average_trip_distance_sds + pow($route->distance - $truck->average_trip_distance, 2);
        $truck_average_stem_distance_sds = $truck_average_stem_distance_sds + pow(($route->first_stem_distance + $route->second_stem_distance) - $truck->average_stem_distance, 2);
        $truck_average_speed_sds = $truck_average_speed_sds + pow($route->average_speed - $truck->average_speed, 2);
        $truck_average_trip_duration_sds = $truck_average_trip_duration_sds + $route->time;
        $truck_average_trip_stop_time_sds = $truck_average_trip_stop_time_sds + $route->short_stops_time;
        $truck_average_trip_traveling_time_sds = $truck_average_trip_traveling_time_sds + $route->traveling_time;
      }
      
      $truck_average_stop_count_per_trip_sd = 0.0;
      $truck_average_trip_distance_sd = 0.0;
      $truck_average_stem_distance_sd = 0.0;
      $truck_average_speed_sd = 0.0;
      $truck_average_trip_duration_sd = 0.0;
      $truck_average_trip_stop_time_sd = 0.0;
      $truck_average_trip_traveling_time_sd = 0.0;
      
      if($truck_trip_count > 0)
      {
        $truck_average_stop_count_per_trip_sd = sqrt($truck_average_stop_count_per_trip_sds / $truck_trip_count);
        $truck_average_trip_distance_sd = sqrt($truck_average_trip_distance_sds / $truck_trip_count);
        $truck_average_stem_distance_sd = sqrt($truck_average_stem_distance_sds / $truck_trip_count);
        $truck_average_speed_sd = sqrt($truck_average_speed_sds / $truck_trip_count);  
        $truck_average_trip_duration_sd = sqrt($truck_average_trip_duration_sds / $truck_trip_count);
        $truck_average_trip_stop_time_sd = sqrt($truck_average_trip_stop_time_sds / $truck_trip_count);
        $truck_average_trip_traveling_time_sd = sqrt($truck_average_trip_traveling_time_sds / $truck_trip_count);
      }
      
      $truck->average_stop_count_per_trip_sd = $truck_average_stop_count_per_trip_sd;
      $truck->average_trip_distance_sd = $truck_average_trip_distance_sd;
      $truck->average_stem_distance_sd = $truck_average_stem_distance_sd;
      $truck->average_speed_sd = $truck_average_speed_sd;
      $truck->average_trip_duration_sd = $truck_average_trip_duration_sd;
      $truck->average_trip_stop_time_sd = $truck_average_trip_stop_time_sd;
      $truck->average_trip_traveling_time_sd = $truck_average_trip_traveling_time_sd;
      $truck->save();
    }
    
    $company_average_stop_count_per_trip_sd = 0.0;
    $company_average_trip_distance_sd = 0.0;
    $company_average_stem_distance_sd = 0.0;
    $company_average_speed_sd = 0.0;
    $company_average_trip_duration_sd = 0.0;
    $company_average_trip_stop_time_sd = 0.0;
    $company_average_trip_traveling_time_sd = 0.0;
    /*
    $company_average_trip_distance_sd.$company_average_stem_distance_sd.
    $company_average_speed_sd.$company_average_trip_duration_sd.
    $company_average_trip_stop_time_sd.$company_average_trip_traveling_time_sd
    */
    if($company_trip_count > 0)
    {
      $company_average_stop_count_per_trip_sd = sqrt($company_average_stop_count_per_trip_sds / $company_trip_count);
      $company_average_trip_distance_sd = sqrt($company_average_trip_distance_sds / $company_trip_count);
      $company_average_stem_distance_sd = sqrt($company_average_stem_distance_sds / $company_trip_count);
      $company_average_speed_sd = sqrt($company_average_speed_sds / $company_trip_count);  
      $company_average_trip_duration_sd = sqrt($company_average_trip_duration_sds / $company_trip_count);
      $company_average_trip_stop_time_sd = sqrt($company_average_trip_stop_time_sds / $company_trip_count);
      $company_average_trip_traveling_time_sd = sqrt($company_average_trip_traveling_time_sds / $company_trip_count);
    }
    
    $company->average_stop_count_per_trip_sd = $company_average_stop_count_per_trip_sd;
    $company->average_trip_distance_sd = $company_average_trip_distance_sd;
    $company->average_stem_distance_sd = $company_average_stem_distance_sd;
    $company->average_speed_sd = $company_average_speed_sd;
    $company->average_trip_duration_sd = $company_average_trip_duration_sd;
    $company->average_trip_stop_time_sd = $company_average_trip_stop_time_sd;
    $company->average_trip_traveling_time_sd = $company_average_trip_traveling_time_sd;
    $company->save();
    $company_average_trip_distance_sd."*".
    $company_average_stem_distance_sd."*".
    $company_average_speed_sd."*".
    $company_average_trip_duration_sd."*".
    $company_average_trip_stop_time_sd."*".$company_average_trip_traveling_time_sd);
  }//actionGenerateStandardDeviation
  
}

?>
