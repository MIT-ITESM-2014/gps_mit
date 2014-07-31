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
			//array('allow',  // allow all users to perform 'index' and 'view' actions
			//	'actions'=>array(),
			//	'users'=>array('*'),
			//),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('getTruckStats', 'getTruckChart1', 'getTrucksChartsInfo'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') == 0)",
			),
			//array('allow', // allow admin user to perform 'admin' and 'delete' actions
			//	'actions'=>array('admin','delete'),
			//	'users'=>array('admin'),
			//),
			//array('deny',  // deny all users
			//	'users'=>array('*'),
			//),
		);
	}


  public function actionGetTruckStats()
  {

    if(!Yii::app()->user->hasState('user'))
      return " ";
    
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

    if(!Yii::app()->user->hasState('user'))
      return " "; 
    
    header('Content-type: application/json');
    
    //TODO Validate session to access company trucks
    
    $criteria = new CDbCriteria(array('order'=>'name ASC'));
    $criteria->addCondition('company_id='. Yii::app()->user->getState('current_company'));
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

    //test for new charts
    $chart_4_new_params = array();
    $chart_4_new_params_2 = array();

    //new chart
    $chart_5_params_average_speed = array();
    $chart_5_params_time_short_stops = array();
    $chart_5_params_time_traveling = array();
    $chart_5_params_no_short_stops = array();
    $chart_5_params_total_distance_traveled = array();

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
      $chart_4_average_distance[] = round($truck->total_distance / $truck->route_count);
      $chart_4_average_speed[] = $truck->total_distance/$truck->route_count;
      $chart_4_fuel_consumption_per_km[] = 1;//$truck->fuel_comsumption_per_km;//TODO
      $chart_4_number_of_trips[] = $truck->route_count; 

      //change seconds to hours to display in highcharts
      $secondsInAMinute = 60;
      $secondsInAnHour = 60 * $secondsInAMinute;

      $time_hours_short_stops = round($truck->short_stops_time / $secondsInAnHour, 1);
      $time_hours_traveling = round($truck->traveling_time / $secondsInAnHour, 1);
      
      //new chart
      $chart_4_new_params [] = array(
        'myData'=> $truck->name,
        'x' => (float)round($truck->total_distance / $truck->route_count, 1),
        'y' => (float)round($truck->average_speed, 1)
        );
      $chart_4_new_params_2 [] = array(
          'myData'=> $truck->name,
          'x' => (float)round($truck->total_distance / $truck->route_count, 1),
          'y' => (float) round($truck->average_stem_distance, 1)
        );
      $chart_5_params_average_speed[] = array(
          'myData'=> $truck->name,
          'x' => (float)round(($time_hours_traveling + $time_hours_short_stops)/$truck->route_count, 1), 
          'y' => (float) round($truck->average_speed, 1)
        );
      $chart_5_params_time_short_stops[] = array(
          'myData'=> $truck->name,
          'x' => (float)round(($time_hours_traveling + $time_hours_short_stops)/$truck->route_count, 1), 
          'y' => (float) round($time_hours_short_stops/$truck->route_count, 1)
        );
      $chart_5_params_time_traveling[] = array(
         'myData'=> $truck->name,
          'x' => (float)round(($time_hours_traveling + $time_hours_short_stops)/$truck->route_count, 1), 
          'y' => (float) round($time_hours_traveling/$truck->route_count, 1) 
        );
      $chart_5_params_no_short_stops[] = array(
          'myData'=> $truck->name,
          'x' => (float)round(($time_hours_traveling + $time_hours_short_stops)/$truck->route_count, 1), 
          'y' => (float) round($truck->average_stop_count_per_trip, 1) 
        );
      $chart_5_params_total_distance_traveled[] = array(
          'myData'=> $truck->name,
          'x' => (float)round(($time_hours_traveling + $time_hours_short_stops)/$truck->route_count, 1), 
          'y' => (float) round($truck->total_distance/$truck->route_count, 1) 
        );
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
        'chart_4_new_params_series' => array(
          'chart_4_data_speed' => $chart_4_new_params,
          'chart_4_data_stem' => $chart_4_new_params_2
        ), 
        'chart_5_params_categories_series' => array(
          'chart_5_data_average_speed' => $chart_5_params_average_speed,
          'chart_5_data_time_short_stops' => $chart_5_params_time_short_stops,
          'chart_5_data_time_traveling' => $chart_5_params_time_traveling,
          'chart_5_data_no_short_stops' => $chart_5_params_no_short_stops,
          'chart_5_data_total_distance_traveled' => $chart_5_params_total_distance_traveled
        )
      );
    echo CJSON::encode($data);
    
    Yii::app()->end(); 
  }
  
  public function actionGetTruckChart1()
  {

    if(!Yii::app()->user->hasState('user'))
      return " ";           
    
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

            //change seconds to hours to display in highcharts
            $secondsInAMinute = 60;
            $secondsInAnHour = 60 * $secondsInAMinute;
            $secondsInADay = 24 * $secondsInAnHour;

            $time_hours_short_stops = round($truck->short_stops_time / $secondsInAnHour, 1);
            $time_hours_long_stops = round($truck->resting_time / $secondsInAnHour, 1);
            $time_hours_traveling = round($truck->traveling_time / $secondsInAnHour, 1);

            $data = 
              array(
                'short_stops_ranges_data'=>array(
                  'stops_0_5'=>array($truck->stops_between_0_5),
                  'stops_5_15'=> array($truck->stops_between_5_15),
                  'stops_15_30'=>array($truck->stops_between_15_30),
                  'stops_30_1'=> array($truck->stops_between_30_60),
                  'stops_1_2'=> array($truck->stops_between_60_120),
                  'stops_2_plus'=> array($truck->stops_between_120_plus)
                ),
                'time_data'=>array(
                  array(
                    'name'=> 'Stop',
                    'color' => '#4acfaf',
                    'y' => $time_hours_short_stops,
                    'drilldown' => 'false',
                  ),
                  array(
                    'name'=> 'Idle',
                    'color' => '#00a995',
                    'y' => $time_hours_long_stops,
                    'drilldown' => 'false',
                  ),
                  array(
                    'name'=> 'Traveling',
                    'color' => '#006161',
                    'y' => $time_hours_traveling,
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
