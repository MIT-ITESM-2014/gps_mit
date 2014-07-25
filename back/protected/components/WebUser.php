<?php
class WebUser extends CWebUser
{
   
   private $_id;
   private $_identity;
   private $_company;
   private $_username;
   private $_isAdmin = false;

   public function getIdentity()
   {
    if($this->_identity == null)
      $this->_identity = Identity::model()->find('id='.(Yii::app()->user->getState('user')));
    return $this->_identity; 
   }
   
   public function getCompany()
   {
    if($this->_company == null)
    {
      $company_id = $this->getIdentity()->company_id;
      $this->_company = Company::model()->find('id='.$company_id);
    }
    return $this->_company;
   }
   
   public function getUsername()
   {
    return $this->_username;
   }
   
   public function setUsername($new_username)
   {
    $this->_username = $new_username;
   }
   
  
   
   public function isAdmin()
   {
    return $this->_isAdmin;
   }
}
?>
