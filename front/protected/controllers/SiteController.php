
<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if(Yii::app()->user->getId()===null)
      $this->redirect(array('site/login'));
    else
      $this->redirect(array('route/index'));
	}
	
	/**
	 * Render the Trucks section
	 */
	public function actionTrucks()
	{

    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));		
	  
	  $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('highcharts');
    $cs->registerCoreScript('screen-block');  
	  $criteria = new CDbCriteria();
	  $criteria->select = 'id, name';
	  $criteria->addCondition("company_id = ".Yii::app()->user->getState('current_company'));
    
    $trucks = CHtml::listData(Truck::model()->findAll($criteria), 'id', 'name');
 
	  $data = array(
	    'trucks' => $trucks,
	  );
	  $this->render('trucks', $data);
	}
	
	/**
	 * Render the Stats section
	 */
	public function actionStats()
	{

    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));

	  $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('highcharts');
    $cs->registerCoreScript('screen-block');  
	  $this->render('stats');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{

    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));

    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery');  

		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

  public function actionErrorIsAdmin()
  {
    
    if(!Yii::app()->user->hasState('user'))
      $this->redirect(array('site/login'));
          
    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery'); 

    $this->render('error_is_admin');
  }
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
			  if(Yii::app()->user->getState('isAdmin'))
			    $this->redirect(array('site/errorIsAdmin'));
			  else
			    $this->redirect(array('company/change'));
				//$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
