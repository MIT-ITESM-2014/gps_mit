
<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if(Yii::app()->user->getId()===null)
            $this->redirect(array('site/login'));
    //elseif(Yii::app()->user->hasState('companies_count'))
/*
    {
      if(Yii::app()->user->getState('companies_count') > 0)
      {
		    $aux = Identity::model()->findByPk(Yii::app()->user->getState('user'));
		    $model=new IdentityCompany('search');
		    $model->unsetAttributes();  // clear any default values
		    $model->identity_id = $aux->id;
		    $this->render('select_company',array(
		      'model'=>$model,
		    ));
      }
      else
      {
        $this->render('no_company',array());  
      }
    }
    */
    elseif(Yii::app()->user->hasState('companies_count'))
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

        //Load a polyline by hand
        routeCoordinates = [".$route_coordinates."];
        

        var color = generateRandomColor();

        route = new google.maps.Polyline({
          path: routeCoordinates,
          geodesic: true,
          strokeColor: color, //'#AAAAA',
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

      function generateRandomColor(){

        return '#'+Math.floor(Math.random()*16777215).toString(16);
      }
      
      function update_stats()
      {
        $.ajax({ 
            type: \"GET\",
            dataType: \"json\",
            url: \"index.php?r=token/getRouteStats&route_id=\"+document.getElementById(\"select-route\").value,
            success: function(data){
              var parsed_data = $.parseJSON(data);
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
        script.src = \"index.php?r=token/getRoute&route_id=\"+document.getElementById(\"select-route\").value;
        temporal_script = document.body.appendChild(script);
        update_stats();
      }
      
      //Add polyline
      //function button_two_action()
      //{
      //  route.setMap(map);
      //}
      
      //Change polyline
      //function button_three_action()
      //{
      //  route.setPath(routeCoordinates);
      //}
      
      //Change polyline
      //function button_four_action()
      //{
      //  route.setPath(routeCoordinates2);
      //}
      
      //Get new route generated at th emoment
      
      
      window.onload = loadScript;";
		  $dataProvider=new CActiveDataProvider('Token');
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
	 * Render the Trucks section
	 */
	public function actionTrucks()
	{
	  $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('highcharts');
    $cs->registerCoreScript('screen-block');  
	  $criteria = new CDbCriteria();
	  $criteria->select = 'id, name';
	  //TODO  Validate company in truck list
	  //$criteria->addCondition("company_id = ".Yii::app()->user->getCompany()->id);
    
    $trucks = CHtml::listData(Truck::model()->findAll($criteria), 'id', 'name');
 
	  $data = array(
	    'trucks' => $trucks,
	  );
	  $this->render('trucks', $data);
	}
	
	/**
	 * Render the Stats section
	 */
	public function actionStats()
	{
	  $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('highcharts');
    $cs->registerCoreScript('screen-block');  
	  $this->render('stats');
	}

  /**
   * Render the truck_options section
   */
  public function actionTruck_Options()
  {
    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('highcharts');
    $cs->registerCoreScript('screen-block');
    $this->render('truck_options');
  }  

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}


  public function actionErrorIsAdmin()
  {
    
    $this->render('error_is_admin');
  }
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
			  if(Yii::app()->user->getState('isAdmin'))
			    $this->redirect(array('site/errorIsAdmin'));
			  else
			    $this->redirect(array('company/change'));
				//$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
