<?php

/**
 * This is the model class for table "company".
 *
 * The followings are the available columns in table 'company':
 * @property integer $id
 * @property integer $has_expected_routes
 * @property double $distance_ratio_long_stop
 * @property double $time_ratio_long_stop
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Identity[] $identities
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
			array('distance_radius_long_stop, time_radius_long_stop, distance_radius_short_stop, time_radius_short_stop', 'numerical', 'integerOnly'=>false),
			array('created_at, updated_at, name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, has_file_in_process, route_count, average_speed, average_stop_count_per_trip, average_trip_distance, average_stem_distance, short_stop_time, traveling_time, resting_time, time_radius_short_stop, distance_radius_short_stop, time_radius_long_stop, distance_radius_long_stop, distance_traveled, average_short_stop_duration, average_trip_duration, average_trip_stop_time, average_trip_traveling_time, average_stop_count_per_trip_sd, average_trip_distance_sd, average_stem_distance_sd, average_speed_sd, average_trip_duration_sd, average_trip_stop_time_sd, average_trip_traveling_time_sd, created_at, updated_at, aux1, aux2, aux3', 'safe', 'on'=>'search'),
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
			'identities' => array(self::HAS_MANY, 'Identity', 'company_id'),
			'trucks' => array(self::HAS_MANY, 'Truck', 'company_id'),
			'trucks_by_average_speed' => array(self::HAS_MANY, 'Truck', 'company_id', 'order'=>'trucks_by_average_speed.average_speed DESC',),
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
			'distance_ratio_long_stop' => 'Distance Ratio Long Stop',
			'time_ratio_long_stop' => 'Time Ratio Long Stop',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'name' => 'Name',
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
		$criteria->compare('distance_ratio_long_stop',$this->distance_ratio_long_stop);
		$criteria->compare('time_ratio_long_stop',$this->time_ratio_long_stop);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('name',$this->name,true);

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
}
