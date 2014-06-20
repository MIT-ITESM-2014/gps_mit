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
				'actions'=>array('create','update', 'uploadOne', 'uploadTwo', 'createPartial'),
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
  
  public function actionUploadTwo()
	{
	  if (empty($_FILES) || $_FILES["file"]["error"]) {
    }
    else
    {
      print_r("va a entrar al segudno archivo");
      //$fileName = $_FILES["file"]["name"];
      $fileName = date('YmdHis').strval(rand()%10);
      move_uploaded_file($_FILES["file"]["tmp_name"], "../files/$fileName");
      $condition_string = 'identity_id=' . Yii::app()->user->getId();
      $uploaded_file_model = UploadedFile::model()->find($condition_string);//
      //Activate after updating database
      //$uploaded_file_model->updated_at = date('Y-m-d H:i:s.u');
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
