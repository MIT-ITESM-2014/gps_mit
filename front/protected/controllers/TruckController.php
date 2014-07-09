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
				'actions'=>array('index','view', 'getTruckStats'),
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
    $json_data = "soy nuaeva data".$_GET['truck_id'];
    
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
    
    /*
    $json_data = '{"routes":[';
        $comma_counter = 0;
        
        foreach($routes as $route)
        {
          if($comma_counter != 0)
            $json_data = $json_data . ",";

          $json_data = $json_data . '{"name":"'.$route->name.'", "value":"'.$route->id.'"}';
          $comma_counter++;
        }

        $json_data = $json_data . ']}';
    */
    
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
