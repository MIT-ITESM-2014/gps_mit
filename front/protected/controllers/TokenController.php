<?php

class TokenController extends Controller
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
		$model=new Token;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Token']))
		{
			$model->attributes=$_POST['Token'];
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

		if(isset($_POST['Token']))
		{
			$model->attributes=$_POST['Token'];
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
    //$rows = user::model()->findAllByAttributes($user, $criteria);
	  $trucks = Truck::model()->findAll();
	  $criteria = new CDbCriteria(array('order'=>'datetime ASC'));
	  $truck_id = $trucks[0]->id;
    $criteria->addCondition('truck_id = '.$truck_id);
    $criteria->addBetweenCondition('datetime', '2013-07-06', '2013-07-07');
	  $samples = Sample::model()->findAll($criteria);
    
	  $script = "function initialize() {
        var mapOptions = {
        zoom: 12,
        center: new google.maps.LatLng(".$samples[0]->latitude.",".$samples[0]->longitude.")
      };

      var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
      //personalize map style
      map.set('styles', [
        {
          featureType: 'road.local',
          elementType: 'geometry',
          stylers: [
            {color: '#96A9C1'},
            { visibility: 'simplified'},
            { weight: 0.9 }
          ]
        },
      {
        featureType: 'road.highway',
        elementType: 'geometry',
        stylers: [
          {color: '#F7DC9E'},
          { visibility: 'simplified'},
          { weight: 5.5 }
        ]
      },
      {
        featureType: 'road',
        elementType: 'labels',
        stylers: [
          { visibility: 'on' },
          { saturation: 600 }
        ]
      },  
      {
        featureType: 'landscape',
        elementType: 'geometry',
        stylers: [
          { hue: '#ffff00' },
          { gamma: 0.5 },
          { saturation: 82 },
          { lightness: 96 }
          ]
      },
      {
        featureType: 'poi.government',
         elementType: 'geometry',
          stylers: [
            { visibility: 'on' },
            { hue: '#9AB896' },
            { lightness: -15 },
            { saturation: 99 }
          ]
        }
      ]);
      
      //Add icon
      //var iconBase = 'http://www.miamidade.gov/transit/mobile/images/';
      //var myLatLng = new google.maps.LatLng(-33.44586, -70.76714 )
      //var marker = new google.maps.Marker({
        //position: myLatLng,
        //map: map,
        //icon: iconBase + 'icon-Bus-Stop.png'
      //});
      map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(
      document.getElementById('map-legend'));

      //LEGEND
      //var legend = document.getElementById('map-legend');

      //GEOJSON
      //map.data.loadGeoJson('http://localhost/gps_mit/front/js/maps/geojson_test.json');
      
      //Load a polyline by hand
      var routeCoordinates = [";
      foreach($samples as $sample)
      {
        $temp_string = " new google.maps.LatLng( ".$sample->latitude.", ".$sample->longitude." ),\n";
        $script = $script.$temp_string;
      }  
      $script = $script."];

      var route = new google.maps.Polyline({
        path: routeCoordinates,
        geodesic: true,
        strokeColor: '#C030FF',
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
    
      route.setMap(map);
      
      var trafficLayer = new google.maps.TrafficLayer();
      trafficLayer.setMap(map);
    }

    function loadScript() {
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' + 'callback=initialize';
      document.body.appendChild(script);
    }
    window.onload = loadScript;";
	
		$dataProvider=new CActiveDataProvider('Token');
		$this->render('index',array(
			//'dataProvider'=>$dataProvider,
			'script'=>$script,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Token('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Token']))
			$model->attributes=$_GET['Token'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Token the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Token::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Token $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='token-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
