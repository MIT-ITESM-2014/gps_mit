<?php

/**
 * This is the model class for table "route".
 *
 * The followings are the available columns in table 'route':
 * @property string $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Sample[] $samples
 * @property ShortStop[] $shortStops
 * @property LongStop[] $longStops
 */
class Route extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'route';
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
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, beginning_stop_id, end_stop_id, truck_id, distance, average_speed, short_stops_count, time, first_stem_distance, first_stem_time, second_stem_distance, second_stem_time,short_stops_time, travelling_time, stops_between_0_5, stops_between_5_15, stops_between_15_30, stops_between_30_60, stops_between_60_120, stops_between_120_plus, average_short_stop_duration, fuel_consumption, is_valid, created_at, updated_at, aux1, aux2, aux3', 'safe', 'on'=>'search'),
			
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
			'samples' => array(self::HAS_MANY, 'Sample', 'route_id','order'=>'datetime ASC'),
			'shortStops' => array(self::HAS_MANY, 'ShortStop', 'route_id', 'order'=>'start_time ASC'),
			'firstShortStop' => array(self::HAS_MANY, 'ShortStop', 'route_id', 'order'=>'start_time ASC', 'limit'=>'1'),
			'lastShortStop' => array(self::HAS_MANY, 'ShortStop', 'route_id', 'order'=>'start_time DESC', 'limit'=>'1'),
			'shortStopsCount' => array(self::STAT, 'ShortStop', 'route_id'),
			'longStops' => array(self::HAS_MANY, 'LongStop', 'route_id'),
			'beginning_stop' => array(self::BELONGS_TO, 'LongStop', 'beginning_stop_id'),
			'end_stop' => array(self::BELONGS_TO,'LongStop', 'end_stop_id'),
			'distanceToNextShortStopSum'=>array(self::STAT,  'ShortStop', 'route_id', 'select' => 'SUM(distance_to_next_stop)'),
			'distanceToNextShortStopCount'=>array(self::STAT, 'ShortStop', 'route_id', 'condition' => 't.distance_to_next_stop IS NOT NULL'),
		);
	}
	
	public function getFirstStemStop()
	{
    $short_stop_count = $this->shortStopsCount;
    $five_percent = intval(round($short_stop_count * 0.05));
    if($short_stop_count > 0)
      return $this->shortStops[0+$five_percent];
    else
      return null;
	}
	
	public function getLastStemStop()
	{
    $short_stop_count = $this->shortStopsCount;
    $five_percent = intval(round($short_stop_count * 0.05));
    if($short_stop_count > 0)
      return $this->shortStops[$short_stop_count-1-$five_percent];
    else
      return null;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('beginning_stop_id',$this->beginning_stop_id,true);
		$criteria->compare('end_stop_id',$this->end_stop_id,true);
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
	 * @return Route the static model class
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
