<?php

//Importing extension for asynchronous processing
Yii::import('ext.runactions.components.ERunActions');

class ParameterFormController extends Controller
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
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),//'create','update','admin','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') != 1)"
			),
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
	  $id = Yii::app()->user->getState('current_company');
	  
	  //If delete has been requested      
    $company_model = Company::model()->findByPk($id);
    if($company_model->has_file_in_process == 2)
	    $this->render('delete_in_process',array());
	  if($company_model->has_file_in_process == 1)
	    $this->render('data_in_process',array());

	  
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['ParameterForm']))
		{
			$model->attributes=$_POST['ParameterForm'];
			if($model->validate())
			{
				//$this->render('data_in_process',array());
				$model->updateCompanyParameters();
				$company_model = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
				$company_model->has_file_in_process = 1;
				$company_model->save();
				echo CJSON::encode(array(
          'status'=>'success'
        ));
		  }
		  else
	    {
	      $error = CActiveForm::validate($model);
        if($error!='[]')
          echo $error;
        Yii::app()->end();
	    }
		}
		else
		  $this->render('update',array(
			  'model'=>$model,
		  ));
	}
    
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the company model to be loaded
	 * @return ParameterForm the loaded model (based on the Company model)
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
	  $parameter_form_model = new ParameterForm;
	  $company_model = Company::model()->findByPk($id);
	  
	  if($company_model->time_radius_short_stop == null)
	    $parameter_form_model->time_radius_short_stop = 50;
	  else
	    $parameter_form_model->time_radius_short_stop = $company_model->time_radius_short_stop;
	  
	  if($company_model->distance_radius_short_stop == null)
	    $parameter_form_model->distance_radius_short_stop = 0.01;
	  else
	    $parameter_form_model->distance_radius_short_stop = $company_model->distance_radius_short_stop;
	  
	  if($company_model->time_radius_long_stop == null)
	    $parameter_form_model->time_radius_long_stop = 14400;
	  else
	    $parameter_form_model->time_radius_long_stop = $company_model->time_radius_long_stop;
	  
	  if($company_model->distance_radius_long_stop == null)
	    $parameter_form_model->distance_radius_long_stop = 0.1;
	  else
	    $parameter_form_model->distance_radius_long_stop = $company_model->distance_radius_long_stop;
	  
	  return $parameter_form_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LongStop $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='parameter-form-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
