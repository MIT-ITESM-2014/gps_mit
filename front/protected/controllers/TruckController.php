<?php

class TruckController extends Controller
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
				'actions'=>array('index','view', 'getTruckStats', 'getTruckChart1', 'getTrucksChartsInfo'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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


  public function actionGetTruckStats()
  {
    header('Content-type: application/json');
    $_GET['truck_id'];
    
    if(isset($_GET['truck_id']))
    {
      if($_GET['truck_id'] > 0)
      {
        $truck_id = $_GET['truck_id'];
        //TODO Validate session to access company trucks
        $truck = Truck::model()->findByPk($truck_id);
        if($truck != null)
        {
          echo CJSON::encode(array("truck"=>$truck));
        }
        else
          echo CJSON::encode("");
      }
      else
        echo CJSON::encode("");
      
    }
    else
      echo CJSON::encode("");
    Yii::app()->end(); 
    
    
  }
  
  public function actionGetTrucksChartsInfo()
  {
    header('Content-type: application/json');
    
    //TODO Validate session to access company trucks
    
    $criteria = new CDbCriteria(array('order'=>'name ASC'));
    $trucks = Truck::model()->findAll($criteria);
    $chart_2_params_categories = array();
    $chart_2_params_series_0_5 = array();
    $chart_2_params_series_5_15 = array();
    $chart_2_params_series_15_30 = array();
    $chart_2_params_series_30_1 = array();
    $chart_2_params_series_1_2 = array();
    $chart_2_params_series_2_plus = array();
    $chart_3_params_series_traveling = array();
    $chart_3_params_series_short_stop = array();
    $chart_3_params_series_long_stop = array();
    $chart_4_average_short_stops_count = array();
    $chart_4_average_short_stops_duration = array();
    $chart_4_time_in_short_stops = array();
    $chart_4_average_distance = array();
    $chart_4_average_speed = array();
    $chart_4_fuel_consumption_per_km = array();
    $chart_4_number_of_trips = array();
    
    foreach($trucks as $truck)
    {
      $chart_2_params_categories[] = $truck->name;
      $chart_2_params_series_0_5[] = $truck->stops_between_0_5;
      $chart_2_params_series_5_15[] = $truck->stops_between_5_15;
      $chart_2_params_series_15_30[] = $truck->stops_between_15_30;
      $chart_2_params_series_30_1[] = $truck->stops_between_30_60;
      $chart_2_params_series_1_2[] = $truck->stops_between_60_120;
      $chart_2_params_series_2_plus[] = $truck->stops_between_120_plus;
      $chart_3_params_series_traveling[] = $truck->traveling_time;
      $chart_3_params_series_short_stop[] = $truck->short_stops_time;
      $chart_3_params_series_long_stop[] = $truck->resting_time;
      $chart_4_average_short_stops_count[] = round($truck->average_stop_count_per_trip);
      $chart_4_average_short_stops_duration[] = 1;// $truck->short_stops_time;//TODO
      $chart_4_average_distance[] = ($truck->total_distance / $truck->route_count);
      $chart_4_average_speed[] = $truck->total_distance/$truck->route_count;
      $chart_4_fuel_consumption_per_km[] = 1;//$truck->fuel_comsumption_per_km;//TODO
      $chart_4_number_of_trips[] = $truck->route_count; 
    }

    $data = 
      array(
        'chart_2_params_categories' => $chart_2_params_categories,
        'chart_2_params_series' => array(
          'chart_2_params_series_0_5' => $chart_2_params_series_0_5,
          'chart_2_params_series_5_15' => $chart_2_params_series_5_15,
          'chart_2_params_series_15_30' => $chart_2_params_series_15_30,
          'chart_2_params_series_30_1' => $chart_2_params_series_30_1,
          'chart_2_params_series_1_2' => $chart_2_params_series_1_2,
          'chart_2_params_series_2_plus' => $chart_2_params_series_2_plus
        ),
        'chart_3_params_series' => array(
          'chart_3_params_series_traveling' => $chart_3_params_series_traveling,
          'chart_3_params_series_short_stop' => $chart_3_params_series_short_stop,
          'chart_3_params_series_long_stop' => $chart_3_params_series_long_stop
        ),
        'chart_4_params_categories_series' => array(
          'average_short_stops_count' => $chart_4_average_short_stops_count,
          'average_short_stops_duration' => $chart_4_average_short_stops_duration,
          'average_distance' => $chart_4_average_distance,
          'average_speed' => $chart_4_average_speed,
          'fuel_consumption_per_km' => $chart_4_fuel_consumption_per_km,
          'number_of_trips' => $chart_4_number_of_trips
        ),
      );
    echo CJSON::encode($data);
    
    Yii::app()->end(); 
  }
  
  public function actionGetTruckChart1()
  {
    header('Content-type: application/json');
    if(isset($_GET['truck_id']))
    {
      if(isset($_GET['truck_id']))
      {
        if($_GET['truck_id'] > 0)
        {
          $truck_id = $_GET['truck_id'];
          $data;
          //TODO Validate session to access company trucks
          $truck = Truck::model()->findByPk($truck_id);
          if($truck != null)
          {
            $data = 
              array(
                'short_stops_ranges_data'=>array(
                  array('0 - 5 min',$truck->stops_between_0_5),
                  array('5 - 15 min', $truck->stops_between_5_15),
                  array('15 - 30 min',$truck->stops_between_15_30),
                  array('30 min - 1 hr', $truck->stops_between_30_60),
                  array('1 hr- 2 hrs', $truck->stops_between_60_120),
                  array('2+ hr', $truck->stops_between_120_plus)
                ),
                'time_data'=>array(
                  array(
                    'name'=> 'Short Stop',
                    'y' => $truck->short_stops_time,
                    'drilldown' => 'false',
                  ),
                  array(
                    'name'=> 'Long Stop',
                    'y' => $truck->resting_time,
                    'drilldown' => 'false',
                  ),
                  array(
                    'name'=> 'Traveling',
                    'y' => $truck->traveling_time,
                    'drilldown' => 'false',
                  ),
                )
              );
            echo CJSON::encode($data);
          }
          else
            echo CJSON::encode("");
        }
        else
          echo CJSON::encode("");
        
      }
      else
        echo CJSON::encode("");
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Truck;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Truck']))
		{
			$model->attributes=$_POST['Truck'];
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

		if(isset($_POST['Truck']))
		{
			$model->attributes=$_POST['Truck'];
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
		$dataProvider=new CActiveDataProvider('Truck');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Truck('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Truck']))
			$model->attributes=$_GET['Truck'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Truck the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Truck::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Truck $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='truck-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
