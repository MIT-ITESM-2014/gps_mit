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
  public $start_date;
  public $end_date;
  public $min_date;
  public $max_date;
  public $active_day;
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
			array('truck_id, latitude, longitude, datetime, route_id, created_at, updated_at', 'required'),
			array('latitude, longitude', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, latitude, longitude, datetime, company_id, route_id, truck_id, truck_name, interval, distance, speed, status_id, sampling_id, created_at, updated_at, aux1, aux2, aux3, truck_name_search, short_datetime_search', 'safe', 'on'=>'search'),
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
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
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
		$criteria->compare('truck_id',$this->truck_id,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('route_id',$this->route_id,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

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
}
