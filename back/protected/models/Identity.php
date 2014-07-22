<?php

/**
 * This is the model class for table "identity".
 *
 * The followings are the available columns in table 'identity':
 * @property integer $id
 * @property string $name
 * @property string $last_name
 * @property string $username
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Token[] $tokens
 */
class Identity extends CActiveRecord
{
  public $is_admin_value;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'identity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, last_name, username, password, updated_at', 'required'),
			array('password', 'length', 'max'=>40),
			array('created_at', 'safe'),
			array('username', 'unique', 'message' => "This username already exists."),
			array('is_admin', 'boolean'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, last_name, username, password, created_at, updated_at, fullname, is_admin', 'safe', 'on'=>'search'),
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
			'tokens' => array(self::HAS_MANY, 'Token', 'identity_id'),
			'uploaded_files' => array(self::HAS_MANY, 'UploadedFile', 'identity_id'),
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
			'last_name' => 'Last Name',
			'username' => 'Username',
			'password' => 'Password',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'is_admin_value' => 'Is administrator',
			'is_admin' => 'Is administrator',
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
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		//$criteria->addNotInCondition('id',array(Yii::app()->user->getState('user')));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

  public function getFullname()
  {
    return $this->last_name.", ".$this->name;
  }
  	
	public function pendingUploads()
  {
    $uploaded_files = $this->uploaded_files(array('condition'=>'step < 2'));
    return $uploaded_files;
    
    /*
    return array(
        'with'=> array("uploaded_files" => array(
          'condition'=> "uploaded_file.step = 1",
        ),
      )
    );
    */
  }
  
  public function pendingUpload()
  {
    $uploaded_files = $this->uploaded_files(array('condition'=>'step < 2'));
    if(count($uploaded_files)>0)
      return $uploaded_files[0];
    else
      return null;
    
  }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Identity the static model class
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
	  //if $this->is_admin_value = 
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
