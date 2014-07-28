<?php

class ParameterForm extends CFormModel
{
	public $time_radius_long_stop;
	public $distance_radius_long_stop;
	public $time_radius_short_stop;
	public $distance_radius_short_stop;
	
	

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('time_radius_long_stop, distance_radius_long_stop, time_radius_short_stop, distance_radius_short_stop', 'required'),
			array('time_radius_long_stop, distance_radius_long_stop, time_radius_short_stop, distance_radius_short_stop', 'numerical', 'integerOnly'=>FALSE, 'message'=>'It must be a number'),
		  array('distance_radius_long_stop','compare','compareAttribute'=>'distance_radius_short_stop','operator'=>'>','message'=>'Idle maximum distance radius must be greater than stop maximum distance radius.'),
			array('time_radius_long_stop','compare','compareAttribute'=>'time_radius_short_stop','operator'=>'>','message'=>'Idle minimum time must be greater than stop minimum time.'),
			array('distance_radius_short_stop','compare','compareAttribute'=>'distance_radius_long_stop','operator'=>'<','message'=>'Stop maximum distance radius must be less than idle maximum distance radius.'),
			array('time_radius_short_stop','compare','compareAttribute'=>'time_radius_long_stop','operator'=>'<','message'=>'Stop minimum time must be less than idle minimum time.'),
			
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
		  'time_radius_long_stop'=>'Idle minimum time',
		  'distance_radius_long_stop'=>'Idle maximum distance radius',
		  'time_radius_short_stop'=>'Stop minimum time',
		  'distance_radius_short_stop'=>'Stop maximum distance radius',
		);
	}
	
	public function updateCompanyParameters()
	{
	  $company = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
	  $company->distance_radius_long_stop = $this->distance_radius_long_stop;
	  $company->time_radius_long_stop = $this->time_radius_long_stop;
	  $company->distance_radius_short_stop = $this->distance_radius_short_stop;
	  $company->time_radius_short_stop = $this->time_radius_short_stop;
	  
	  if($company->validate())
	    $company->save();
	}
}
