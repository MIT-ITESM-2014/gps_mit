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
			//array('allow',  // allow all users to perform 'index' and 'view' actions
			//	'actions'=>array(),
			//	'users'=>array('*'),
			//),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'change','index','view','getCompanyStats'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') == 0)",
			),
			//array('allow', // allow admin user to perform 'admin' and 'delete' actions
			//	'actions'=>array('admin','delete', 'change'),
			//	'users'=>array('admin'),
			//),
			//array('deny',  // deny all users
			//	'users'=>array('*'),
			//),
		);
	}
	
	public function actionGetCompanyStats()
  {
    header('Content-type: application/json');
    $current_company = Yii::app()->user->getState('current_company');
    $company_model = Company::model()->findByPk($current_company);
    
    $charts_params_x_axis = array();
    $chart_1_trucks_speeds = array();
    $chart_1_routes_speeds = array();
    $chart_2_trucks_stem = array();
    $chart_2_routes_stem = array();
    $chart_3_trucks_trip_distance = array();
    $chart_3_routes_trip_distance = array();
    $chart_4_trucks_duration = array();
    $chart_4_routes_duration = array();

    //general stats 
    $average_short_stop_duration = "";
    $hours = (int)($company_model->average_short_stop_duration / 3600); 
    if( $hours > 0)
    {
      $average_short_stop_duration = $average_short_stop_duration.$hours."h ";
    }
    $time_left = ($company_model->average_short_stop_duration - ($hours * 3600));
    $minutes = (int)($time_left/60);
    if( $minutes > 0)
    {
      $average_short_stop_duration = $average_short_stop_duration.$minutes."min ";
    }

    //truck stats
    foreach ($company_model->trucks as $truck) {
    	$charts_params_x_axis [] = $truck->id;
    	$chart_1_trucks_speeds [] = array(
    		'x' => $truck->id, //TODO change id for number assigned for ordering
    		'y' => (float)round($truck->average_speed, 1),
    		'name' => $truck->name,    		
    		'myData' => (float)round(22.88, 1) // TODO insert standard deviation here
    	);
    	$chart_2_trucks_stem [] = array(
    		'x' => $truck->id,
    		'y' => (float)round($truck->average_stem_distance),
    		'name' => $truck->name,    		
    		'myData' => (float)round(6.234, 1) //TODO insert real std. dev here
    	);
    	$chart_3_trucks_trip_distance [] = array(
    		'x'=> $truck->id,
    		'y' => (float) round($truck->average_trip_distance, 1),
    		'name' => $truck->name,
    		'myData' => (float) round(3624.26, 1) //TODO insert real std dev
    	);
    	$chart_4_trucks_duration [] = array(
    		'x'=> $truck->id,
    		'y' => (float) round($truck->average_duration, 1),
    		'name' => $truck->name,    	
    		'myData' => (float)round(374.747, 1) //TODO insert real std. dev here
    	);
    	foreach ($truck->routes as $route) {
    		$chart_1_routes_speeds [] = array(
    			'x' => $truck->id, 			
    			'y' => (float) round($route->average_speed, 1),
    			'name' => $truck->name    			
    		);
    		$chart_2_routes_stem [] = array(
    			'x' => $truck->id,
    			'y' => (float) round($route->first_stem_distance + $route->second_stem_distance, 1),
    			'name' => $truck->name
    		);
    	}
    }
    
    $data = array(
      'total_trips' => $company_model->route_count,
      'average_short_stop_duration' => $average_short_stop_duration,
      'distance_traveled' => number_format($company_model->distance_traveled, 1).' km',
      'charts_params_x_axis' => $charts_params_x_axis,
      'chart_1_spline_data' => $chart_1_trucks_speeds,
      'chart_1_scatter_data' => $chart_1_routes_speeds,
      'chart_2_spline_data' => $chart_2_trucks_stem,
      'chart_2_scatter_data' => $chart_2_routes_stem,
      'chart_4_spline_data' => $chart_4_trucks_duration,
    );
    
    if($data != null)
    {
      echo CJSON::encode($data);
    }
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
	
	public function actionChange()
	{
	  $identity_company_model = null;
	  if(isset($_GET['company']))
	    $identity_company_model = IdentityCompany::model()->findByAttributes(array('company_id'=>$_GET['company'],'identity_id'=>Yii::app()->user->getState('user')));
	  if(isset($_GET['company']) && !empty($identity_company_model))
	  {
      Yii::app()->user->setState('current_company', $identity_company_model->company_id);
      Yii::app()->user->setState('current_company_name', $identity_company_model->company->name);
      $this->redirect(array('site/index')); 
	  }
	  else
	  {
      $aux = Identity::model()->findByPk(Yii::app()->user->getState('user'));
	    $model=new IdentityCompany('search');
	    $model->unsetAttributes();  // clear any default values
	    $model->identity_id = $aux->id;
	    if(isset($_GET['IdentityCompany']))
	    {
	      $model->company_name_search=$_GET['IdentityCompany']['company_name_search'];
	    }
	    $this->render('change',array(
	      'model'=>$model,
	    ));
	  }
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Company;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Company']))
		{
			$model->attributes=$_POST['Company'];
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

		if(isset($_POST['Company']))
		{
			$model->attributes=$_POST['Company'];
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
		$dataProvider=new CActiveDataProvider('Company');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
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
