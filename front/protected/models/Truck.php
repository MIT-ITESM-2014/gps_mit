<?php

/**
 * This is the model class for table "truck".
 *
 * The followings are the available columns in table 'truck':
 * @property string $id
 * @property string $name
 * @property string $uploaded_file_id
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Sample[] $samples
 * @property UploadedFile $uploadedFile
 */
class Truck extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'truck';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, created_at, updated_at', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, company_id, total_distance, route_count, average_duration, average_speed, average_stop_count_per_trip, average_distance_between_short_stops, average_stem_distance, average_trip_distance, short_stops_time, traveling_time, resting_time, stops_between_0_5, stops_between_5_15, stops_between_15_30, stops_between_30_60, stops_between_60_120, stops_between_120_plus, average_trip_stop_time, average_trip_traveling_time,  average_trip_duration_sd, average_trip_stop_time_sd, average_trip_traveling_time_sd, created_at, updated_at, aux1, aux2, aux3', 'safe', 'on'=>'search'),
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
			'samples' => array(self::HAS_MANY, 'Sample', 'truck_id'),
			'samplings' => array(self::HAS_MANY, 'Sampling', 'truck_id'),
			'routes' => array(self::HAS_MANY, 'Route', 'truck_id', 'condition' => 'routes.is_valid = 1'),
      'routesCount'=>array(self::STAT, 'Route', 'truck_id','condition' => 't.is_valid = 1'),
			'timeSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(time)','condition' => 't.is_valid = 1'),
      'firstStemTimeSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(first_stem_time)','condition' => 't.is_valid = 1'),
      'secondStemTimeSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(second_stem_time)','condition' => 't.is_valid = 1'),
      'averageSpeedSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(average_speed)','condition' => 't.is_valid = 1'),
      'shortStopsCountSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(short_stops_count)','condition' => 't.is_valid = 1'),
      'distanceSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(distance)','condition' => 't.is_valid = 1'),
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
		$criteria->compare('name',$this->name,true);
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
	 * @return Truck the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
