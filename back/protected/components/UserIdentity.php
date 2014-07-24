<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	 
	private $_id;
	 
	public function authenticate()
	{
	  $identity_model=Identity::model()->findByAttributes(array('username'=>$this->username));
	  
	  if($identity_model === null)
	  {
	    $this->errorCode=self::ERROR_USERNAME_INVALID;
	  }
	  elseif($identity_model->password!==$this->password)
	  {
	    $this->errorCode=self::ERROR_PASSWORD_INVALID;
	  }
		else
		{
			Yii::app()->user->setUsername($this->username);
		  Yii::app()->user->setState('user', $identity_model->id);
		  Yii::app()->user->setState('first_name', $identity_model->name);
		  if($identity_model->is_admin == 1)
		  {
		    Yii::app()->user->setState('isAdmin', true);
		  }
		  else
		  {
		    Yii::app()->user->setState('isAdmin', false);
		    
		    $companies_model=IdentityCompany::model()->findAllByAttributes(array('identity_id'=>$identity_model->id));
		    if(empty($companies_model))
	        Yii::app()->user->setState('companies_count', 0);
	      else
	      {
	        Yii::app()->user->setState('companies_count', count($companies_model));
	        Yii::app()->user->setState('current_company', $companies_model[0]->id);
	        Yii::app()->user->setState('current_company_name', $companies_model[0]->company->name);
	        
	      }
		  }

			$this->errorCode=self::ERROR_NONE;		
		}
	 
		return !$this->errorCode;
	
	}
	  /*This is the actual function that will return the id for the CWebUser
	  */
	 public function getId()
   {
    return 1;
    //return $this->_id;
   }
   
   
   public function getIdentity()
   {
    return Identity::model()->find('id='.($this->_id/456345));
   }
   
   public function getCompany()
   {
    $identity = $this->getIdentity();
    $company_id = $identity->company_id;
    $company = Company::model()->find('id='.$company_id);
    return $company;
   }
}
