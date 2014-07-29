<?php

class IdentityCompanyController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','view','create','update'),
				'expression'=> "(Yii::app()->user->getState('isAdmin') == 1)"
			),
		);
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new IdentityCompany;
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

    if(isset($_GET['company_id']))
    {
    	$company_name = "";
      $model->company_id = $_GET['company_id'];
      $company_model = Company::model()->findByPk($model->company_id);
	    if(!empty($company_model))
		 	{
	 	 		$company_name = $company_model->name;
		 	}

	    if(isset($_POST['IdentityCompany']))
	    {
		    $model->attributes=$_POST['IdentityCompany'];
		    $model->company_id = $_GET['company_id'];

		    if($model->save())
			    $this->redirect(array('admin','company_id' => $_GET['company_id']));
	    }
	    $criteria = new CDbCriteria;
	    $criteria->select = array('identity_id');
	    $criteria->addCondition('t.company_id = '.$model->company_id);
	    $current_identities = IdentityCompany::model()->findAll($criteria);
	    $current_identities_array = array();
	    
	    foreach($current_identities as $identity)
	    {
	      $current_identities_array[] = $identity->identity_id;
	    }
	    $criteria=new CDbCriteria;
	    
	    $criteria->addNotInCondition('id', $current_identities_array);
      $criteria->addCondition('is_admin != 1');
      $dropdown_data = CHtml::listData(Identity::model()->findAll($criteria),'id','fullname');
      
      $this->render('create',array(
		    'model'=>$model,
		    'dropdown_data'=>$dropdown_data,
		    'company_name' => $company_name,
	    ));
	  }
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
	  $aux = Identity::model()->findByPk(Yii::app()->user->getState('user'));
		$model=new IdentityCompany('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['company_id']))
		{
		  $model->company_id = $_GET['company_id'];
		  $company_model = Company::model()->findByPk($model->company_id);
		  $company_name = "";
		  if(!empty($company_model))
		  	$company_name = $company_model->name;
		  if(isset($_GET['IdentityCompany']))
			  $model->attributes=$_GET['IdentityCompany'];

		  $this->render('admin',array(
			  'model'=>$model,
			  'company_id'=>$_GET['company_id'],
			  'company_name'=>$company_name,
		  ));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IdentityCompany the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IdentityCompany::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IdentityCompany $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='identity-company-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
