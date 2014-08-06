<?php

//Importing extension for asynchronous processing
Yii::import('ext.runactions.components.ERunActions');

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
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array( 'uploadOne', 'createPartial', 'submitParameters', 'sendParameters', 'admin','delete', 'create', 'processData'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') != 1)"
			),
		);
	}
	
	public function createNewParametersForm()
	{
    if(!Yii::app()->user->hasState('user'))
      return " ";
	  $parameter_form_model = new ParameterForm;
	  $company_model = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
	  
	  if($company_model->time_radius_short_stop == null)
	    $parameter_form_model->time_radius_short_stop = 50;
	  else
	    $parameter_form_model->time_radius_short_stop = $company_model->time_radius_short_stop;
	  
	  if($company_model->distance_radius_short_stop == null)
	    $parameter_form_model->distance_radius_short_stop = 0.05;
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
	
	public function actionCreate()
	{
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
	  if((!Yii::app()->user->isGuest) && (Yii::app()->user->hasState('current_company')))
	  {
	    $company_id = Yii::app()->user->getState('current_company');
	    $company_model = Company::model()->findByPk($company_id);
	    
		  $step = 0;

		  if($company_model->has_file_in_process == 1)
		  {
		    $pending_files = $company_model->uploaded_files;
	      if(count($pending_files) > 0)
	        $step = $pending_files[0]->step;
		  }
		
		  if($step == 2)
		    $this->render('file_in_process',array());
		  else
		  {
		    $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload.full.min.js', CClientScript::POS_HEAD);
        $cs->registerScriptFile($baseUrl.'/vendors/plupload/plupload_init.js', CClientScript::POS_END);
	      $parameter_model = $this->createNewParametersForm();
        $script = isset($_GET['script']);
        	    
		    $this->render('create',array(
          'step'=>$step,
          'parameter_model'=>$parameter_model,
          'script'=>$script
	      ));
	    }
	  }
	  else
	    $this->redirect(array('login/'));
	}
	
  
	public function actionUploadOne()
	{
	  if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
	  $company_id = Yii::app()->user->getState('current_company');
	  $company_model = Company::model()->findByPk($company_id);
	  if($company_model->has_file_in_process != 1)
	  {
	    if ( empty($_FILES) || $_FILES["file"]["error"] ) {
      }
      else
      {
        $fileName = date('YmdHis').strval(rand()%10);
        move_uploaded_file($_FILES["file"]["tmp_name"], "../files/$fileName");
        $uploaded_file_model = new UploadedFile;
        $uploaded_file_model->company_id = $company_id;
        $uploaded_file_model->filename = $fileName;
        $uploaded_file_model->step = 0;
        $uploaded_file_model->save();
        $company_model->has_file_in_process = 1;
        $company_model->save();
        $uploaded_file_model->step = 1;
        $uploaded_file_model->save();
		  }
		}
	  else
	    $this->render('file_in_process',array());
  }
  
  
  //Uses five more seconds
  
  public function actionCreatePartial()
  {
    if(!Yii::app()->user->hasState('user'))
      return " ";
    $company_id = Yii::app()->user->getState('current_company');
	  $company_model = Company::model()->findByPk($company_id);
	  if($company_model != null)
	  {
      $parameter_model = $this->createNewParametersForm();
		
		  $script = isset($_GET['script']);
		  $data = array(
        'step'=>1,
        'parameter_model'=>$parameter_model,
        'script'=>$script,
      );
      if($script)
        echo $this->renderPartial('_ajaxContent', $data, true, true);
      else 
        $this->renderPartial('_ajaxContent', $data, true, true);
    }
  }
  
  
    
  public function actionSubmitParameters()
  { 
    error_log("paso 11");
    $company_id = Yii::app()->user->getState('current_company');
    $company_model = Company::model()->findByPk($company_id);
    if($company_model->has_file_in_process == 1)//Si está en procesamiento
    {
    error_log("paso 12");
      $parameter_model = $this->createNewParametersForm();
      if(isset($_POST['ParameterForm']))
	    {
	    error_log("paso 13");
		    $parameter_model->attributes=$_POST['ParameterForm'];
		    if($parameter_model->validate())
		    {
		    error_log("paso 14");
		      $parameter_model->updateCompanyParameters();
		      $uploaded_file_model = UploadedFile::model()->findByAttributes(array('company_id'=>Yii::app()->user->getState('current_company')));
          $uploaded_file_model->step = 2;
          $uploaded_file_model->save();
          echo CJSON::encode(array(
            'status'=>'success'
          ));
		    }
		    else
		    {
		    error_log("paso 15");
		      $error = CActiveForm::validate($parameter_model);
          if($error!='[]')
            echo $error;
          Yii::app()->end();
		    }
	    }
	  }
  }
  
  public function actionSendParameters()
  {
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
    $company_id = Yii::app()->user->getState('current_company');
	  $company_model = Company::model()->findByPk($company_id);
	  if($company_model->has_file_in_process == 1)
	  {
      $parameter_model = $this->createNewParametersForm();
      if(isset($_POST['ParameterForm']))
		  {
			  $parameter_model->attributes=$_POST['ParameterForm'];
			  if($parameter_model->validate())
			  {
			    echo CJSON::encode(array(
            'status'=>'success'
          ));
				  $this->refresh();
			  }
			  else
			  {
			    $error = CActiveForm::validate($parameter_model);
          if($error!='[]')
              echo $error;
          Yii::app()->end();
			  }
		  }
		}
	  else
	  {
	    $this->render('file_in_process',array());
	  }
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
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
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
