<?php

/**
 * This is the model class for table "company".
 *
 * The followings are the available columns in table 'company':
 * @property integer $id
 * @property string $name
 * @property integer $has_expected_routes
 * @property string $route_count
 * @property double $time_radius_short_stop
 * @property double $distance_radius_short_stop
 * @property double $time_radius_long_stop
 * @property double $distance_radius_long_stop
 * @property double $distance_traveled
 * @property double $average_short_stop_duration
 * @property double $fuel_consumption
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property IdentityCompany[] $identityCompanies
 * @property Truck[] $trucks
 */
class Company extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		  array('name', 'required'),
			array('has_expected_routes', 'numerical', 'integerOnly'=>true),
			array('time_radius_short_stop, distance_radius_short_stop, time_radius_long_stop, distance_radius_long_stop, distance_traveled, average_short_stop_duration, fuel_consumption', 'numerical'),
			array('name, route_count, created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, has_expected_routes, has_file_in_process, route_count, time_radius_short_stop, distance_radius_short_stop, time_radius_long_stop, distance_radius_long_stop, distance_traveled, average_short_stop_duration, fuel_consumption, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'identityCompanies' => array(self::HAS_MANY, 'IdentityCompany', 'company_id'),
			'trucks' => array(self::HAS_MANY, 'Truck', 'company_id'),
			'uploaded_files' => array(self::HAS_MANY, 'UploadedFile', 'company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'has_expected_routes' => 'Has Expected Routes',
			'route_count' => 'Route Count',
			'time_radius_short_stop' => 'Time Radius Short Stop',
			'distance_radius_short_stop' => 'Distance Radius Short Stop',
			'time_radius_long_stop' => 'Time Radius Long Stop',
			'distance_radius_long_stop' => 'Distance Radius Long Stop',
			'distance_traveled' => 'Distance Traveled',
			'average_short_stop_duration' => 'Average Short Stop Duration',
			'fuel_consumption' => 'Fuel Consumption',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('has_expected_routes',$this->has_expected_routes);
		$criteria->compare('route_count',$this->route_count,true);
		$criteria->compare('time_radius_short_stop',$this->time_radius_short_stop);
		$criteria->compare('distance_radius_short_stop',$this->distance_radius_short_stop);
		$criteria->compare('time_radius_long_stop',$this->time_radius_long_stop);
		$criteria->compare('distance_radius_long_stop',$this->distance_radius_long_stop);
		$criteria->compare('distance_traveled',$this->distance_traveled);
		$criteria->compare('average_short_stop_duration',$this->average_short_stop_duration);
		$criteria->compare('fuel_consumption',$this->fuel_consumption);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('has_file_in_process',$this->has_file_in_process,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Company the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
		protected function beforeSave()
	{
	  if(parent::beforeSave())
	  {
	    if($this->isNewRecord)
	      $this->created_at = date('Y-m-d H:i:s.u');
	    $this->updated_at = date('Y-m-d H:i:s.u');
	  }
	  return true;
	}
	
	protected function beforeValidate()
	{
	  if(parent::beforeValidate())
	  {
	    if($this->isNewRecord)
	      $this->created_at = date('Y-m-d H:i:s.u');
	    $this->updated_at = date('Y-m-d H:i:s.u');
	  }
	  return true;
	}
	
	protected function beforeUpdate()
	{
	  if(parent::beforeUpdate())
	  {
	    $this->updated_at = date('Y-m-d H:i:s.u');
	  }
	  return true;
	}
	
}
