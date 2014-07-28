<?php

/**
 * This is the model class for table "uploaded_file".
 *
 * The followings are the available columns in table 'uploaded_file':
 * @property string $id
 * @property string $truck_file
 * @property string $filename
 * @property integer $identity_id
 * @property double $step
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Truck[] $trucks
 * @property Identity $identity
 */
class UploadedFile extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'uploaded_file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('truck_file, identity_id, step, created_at', 'required'),
			array('identity_id', 'numerical', 'integerOnly'=>true),
			array('step', 'numerical'),
			array('truck_file, filename', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, filename, company_id, step, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'trucks' => array(self::HAS_MANY, 'Truck', 'uploaded_file_id'),
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
			'truck_file' => 'Truck File',
			'filename' => 'Filename',
			'identity_id' => 'Identity',
			'step' => 'Step',
			'created_at' => 'Created At',
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
		$criteria->compare('truck_file',$this->truck_file,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('identity_id',$this->identity_id);
		$criteria->compare('step',$this->step);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function scopes()
	{
	  return array(
	    'pending'=>array(
	      'condition'=>'step=1',
	    ),
	  );
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UploadedFile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
