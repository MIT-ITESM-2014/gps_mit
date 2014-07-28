<?php

/**
 * This is the model class for table "long_stop".
 *
 * The followings are the available columns in table 'long_stop':
 * @property string $id
 * @property string $route_id
 * @property double $latitude
 * @property double $longitude
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Route $route
 */
class LongStop extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'long_stop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('latitude, longitude, created_at, updated_at', 'required'),
			array('latitude, longitude', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, latitude, longitude, start_time, end_time, duration, created_at, updated_at, aux1, aux2, aux3', 'safe', 'on'=>'search'),
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
	  	'routeBeginning' => array(self::HAS_MANY, 'Route', 'beginning_stop_id'),
			'routeEnding' => array(self::HAS_MANY, 'Route', 'end_stop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
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
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('duration',$this->duration);
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
	 * @return LongStop the static model class
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
