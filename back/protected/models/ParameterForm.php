<?php

class ParameterForm extends CFormModel
{
	public $time_ratio_long_stop;
	public $distance_ratio_long_stop;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('time_ratio_long_stop, distance_ratio_long_stop', 'required'),
			array('time_ratio_long_stop, distance_ratio_long_stop', 'numerical', 'integerOnly'=>FALSE, 'message'=>'It must be a number'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
		  'time_ratio_long_stop'=>'Time ratio',
		  'distance_ratio_long_stop'=>'Distance ratio',
		);
	}
	
	public function updateCompanyParameters()
	{
	  $company = Yii::app()->user->getCompany();
	  $company->distance_ratio_long_stop = $this->distance_ratio_long_stop;
	  $company->time_ratio_long_stop = $this->time_ratio_long_stop;
	  
	  if($company->validate())
	    $company->save();
	}
}
