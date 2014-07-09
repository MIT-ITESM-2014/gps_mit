<?php

/**
 * This is the model class for table "truck".
 *
 * The followings are the available columns in table 'truck':
 * @property string $id
 * @property string $identifier
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
			array('id, identifier, name, uploaded_file_id, average_stem_distance, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'routes' => array(self::HAS_MANY, 'Route', 'truck_id'),
			'uploadedFile' => array(self::BELONGS_TO, 'UploadedFile', 'uploaded_file_id'),
      'routesCount'=>array(self::STAT, 'Route', 'truck_id'),
			'timeSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(time)'),
      'averageSpeedSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(average_speed)'),
      'shortStopsCountSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(short_stops_count)'),
      'distanceSum'=>array(self::STAT,  'Route', 'truck_id', 'select' => 'SUM(distance)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'identifier' => 'Identifier',
			'name' => 'Name',
			'uploaded_file_id' => 'Uploaded File',
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
		$criteria->compare('identifier',$this->identifier,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('uploaded_file_id',$this->uploaded_file_id,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
