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
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
		  $this->_id = 1 * 456345; //Must be divided by 456345 to get the real ID
		  //$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
		  //Yii::app()->user->login($this, $duration);
		  //$user = Yii::app()->user->setId(1 * 456345);
		  //To add more information to the User
		  $this->setState('safe_id', 1 * 456345);//Must be divided by 456345 to get the real ID
		  Yii::app()->user->setId("3");
		  print_r(Yii::app()->user->getId());
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
