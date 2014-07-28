<?php

/**
 * This is the model class for table "sample".
 *
 * The followings are the available columns in table 'sample':
 * @property string $id
 * @property string $truck_id
 * @property double $latitude
 * @property double $longitude
 * @property string $datetime
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Truck $truck
 */
class Sample extends CActiveRecord
{
  public $truck_name_search;
  public $short_datetime_search;
  public $trucks_array_for_search = null;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sample';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('latitude, longitude, datetime, created_at, updated_at, status_id', 'required'),
			array('latitude, longitude, status_id', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, latitude, longitude, datetime, route_id, truck_id, truck_name, interval, distance, speed, status_id, sampling_id, created_at, updated_at, aux1, aux2, aux3, truck_name_search, short_datetime_search', 'safe', 'on'=>'search'),
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
			'truck' => array(self::BELONGS_TO, 'Truck', 'truck_id'),
			'sampling' => array(self::BELONGS_TO, 'Sampling', 'truck_id'),
			'route' => array(self::BELONGS_TO, 'Route', 'truck_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'truck_id' => 'Truck',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'datetime' => 'Datetime',
			'route_id' => 'Route',
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

		$criteria->compare('id',$this->id,true);
		//$criteria->compare('truck_id',$this->truck_id,true);
		$criteria->addInCondition('truck_id',$this->getTrucksArray());
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('datetime::text',$this->datetime,true);
		$criteria->compare('route_id',$this->route_id,true);
		$criteria->compare('status_id',$this->route_id,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->with = array('truck');
		$criteria->compare("LOWER(truck.name)", strtolower($this->truck_name_search), true);
		
    //$criteria->compare("LOWER(truck.name)", strtolower($this->short_datetime_search), true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sample the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getTrucksArray()
	{
	  if ($this->trucks_array_for_search == null)
	  {
	    $this->trucks_array_for_search = array();
	    $company_model = Company::model()->findByPk(Yii::app()->user->getState('current_company'));
	    foreach($company_model->trucks as $truck)
	    {
	      $this->trucks_array_for_search[] = $truck->id;
	    }
	    
	  }
	  return $this->trucks_array_for_search;
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
