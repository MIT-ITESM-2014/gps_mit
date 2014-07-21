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
    else
		  $this->render('index');
	}
	
	public function actionReset()
	{
	  if(isset($_GET['reset_confirmation']))
	  {
	    
	    $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
      $trucks_ids = array();
      foreach($trucks as $truck)
        $trucks_ids[] = $truck->id;
      
      $criteria_find_routes = new CDbCriteria();
      $criteria_find_routes->select='id';
      $criteria_find_routes->addInCondition('truck_id', $trucks_ids);
      $criteria_find_routes->with(array('beginning_stop', 'end_stop'));
      $routes = Routes::model()->findAllByAttributes($criteria_find_routes);
	    $routes_ids = array();
	    $long_stops_ids = array();
	    foreach($routes as $route)
	    {
	      $routes_ids[] = $route->id;
	      $long_stops_ids[] = $route->beginning_stop->id;
	      $long_stops_ids[] = $route->end_stop->id;
	    }
	    
	    //Sample
	    $criteria_sample = new CDbCriteria();
      $criteria_sample->addInCondition('truck_id', $trucks_ids);
	    Sample::model()->deleteAll($criteria_sample);
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
	    LongStop::model()->deleteAll($criteria_route);
	    //Truck
	    $criteria_truck = new CDbCriteria();
      $criteria_truck->addInCondition('company_id', Yii::app()->user->getState('current_company'));
	    Truck::model()->deleteAll($criteria_truck);
	    $this->render('successful_reset');
	  }
	  else
	  {
	    $this->render('reset_form');
	  }
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
				$this->redirect(Yii::app()->user->returnUrl);
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
