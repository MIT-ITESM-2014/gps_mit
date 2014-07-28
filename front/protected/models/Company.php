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
			array('has_expected_routes', 'numerical', 'integerOnly'=>true),
			array('distance_radius_long_stop, time_radius_long_stop, distance_radius_short_stop, time_radius_short_stop', 'numerical', 'integerOnly'=>false),
			array('created_at, updated_at, name', 'safe'),
			array('distance_radius_long_stop','compare','compareAttribute'=>'distance_radius_short_stop','operator'=>'>','message'=>'Long stop distance radius must be greater than short stop distance radius.'),
			array('time_radius_long_stop','compare','compareAttribute'=>'time_radius_short_stop','operator'=>'>','message'=>'Long stop time radius must be greater than short stop time radius.'),
			array('distance_radius_short_stop','compare','compareAttribute'=>'distance_radius_long_stop','operator'=>'<','message'=>'Short stop distance radius must be less than long stop distance radius.'),
			array('time_radius_short_stop','compare','compareAttribute'=>'time_radius_long_stop','operator'=>'<','message'=>'Short stop time radius must be less than long stop time radius.'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, has_expected_routes, has_file_in_process, route_count, time_radius_short_stop, distance_radius_short_stop, time_radius_long_stop, distance_radius_long_stop, distance_traveled, average_short_stop_duration, fuel_consumption, created_at, updated_at, aux1, aux2, aux3', 'safe', 'on'=>'search'),
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
			'has_expected_routes' => 'Has Expected Routes',
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
		$criteria->compare('has_expected_routes',$this->has_expected_routes);
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
