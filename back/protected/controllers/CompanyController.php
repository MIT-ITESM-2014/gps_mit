<?php

class CompanyController extends Controller
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
			
			
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'create', 'update'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') == 1)"
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('reset', 'change'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') != 1)"
			),
			array('deny',  // deny all users
			  'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
			),
			
		);
	}

	

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
		$model=new Company;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Company']))
		{
			$model->attributes=$_POST['Company'];
			$model->has_file_in_process = 0;
			if($model->save())
			{
        $this->redirect(array('admin'));
		  }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionChange()
	{
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
	  if(isset($_GET['company']))
	  {
	    $identity_company_model = IdentityCompany::model()->findByAttributes(array('company_id'=>$_GET['company'],'identity_id'=>Yii::app()->user->getState('user')));
	    if(!empty($identity_company_model))
	    {
	      Yii::app()->user->setState('current_company', $identity_company_model->company_id);
	      Yii::app()->user->setState('current_company_name', $identity_company_model->company->name);
	    }
	  }
    $aux = Identity::model()->findByPk(Yii::app()->user->getState('user'));
	  $model=new IdentityCompany('search');
	  $model->unsetAttributes();  // clear any default values
	  $model->identity_id = $aux->id;
	  $this->render('change',array(
	    'model'=>$model,
	  ));
	}


  public function actionReset()
	{
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
    if(isset($_POST['reset_confirmation']))
    {
      
      $trucks = Truck::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
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
      
      //Sample
      $criteria_sample = new CDbCriteria();
      $criteria_sample->addInCondition('truck_id', $trucks_ids);
      Sample::model()->deleteAll($criteria_sample);
      $criteria_sample = new CDbCriteria();
      $criteria_sample->condition = 'company_id = '. Yii::app()->user->getState('current_company');
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
      LongStop::model()->deleteAll($criteria_long_stop);
      //Truck
      $criteria_truck = new CDbCriteria();
      $criteria_truck->condition = 'company_id = '. Yii::app()->user->getState('current_company');
      Truck::model()->deleteAll($criteria_truck);
      
      $uploaded_files = UploadedFile::model()->findAllByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
      foreach($uploaded_files as $uploaded_file)
      {
        unlink("../files/".$uploaded_file->filename);
        $uploaded_file->delete();
      }
      
      $company = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
      $company->has_file_in_process = 0;
      $company->route_count = null;
      $company->average_speed = null;
      $company->average_stop_count_per_trip = null;
      $company->average_trip_distance = null;
      $company->average_stem_distance = null;
      $company->short_stop_time = null;
      $company->traveling_time = null;
      $company->resting_time = null;
      $company->time_radius_short_stop = null;
      $company->distance_radius_short_stop = null;
      $company->time_radius_long_stop = null;
      $company->distance_radius_long_stop = null;
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
      
      $this->render('successful_reset');
      
    }
    else
    {
      $this->render('reset_form');
    }
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Company']))
		{
			$model->attributes=$_POST['Company'];
			if($model->save())
			{
			  $this->redirect(array('admin'));
		  }
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
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Company('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Company']))
			$model->attributes=$_GET['Company'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Company the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Company::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Company $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='company-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
