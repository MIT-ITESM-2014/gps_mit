<?php

/**
 * This is the model class for table "identity_company".
 *
 * The followings are the available columns in table 'identity_company':
 * @property integer $id
 * @property integer $identity_id
 * @property integer $company_id
 *
 * The followings are the available model relations:
 * @property Company $company
 * @property Identity $identity
 */
class IdentityCompany extends CActiveRecord
{
  public $fullname_search;
  public $company_name_search;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'identity_company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('identity_id, company_id', 'required'),
			array('identity_id, company_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, identity_id, company_id, fullname_search, company_name_search', 'safe', 'on'=>'search'),
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
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
			'identity' => array(self::BELONGS_TO, 'Identity', 'identity_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'identity_id' => 'Identity',
			'company_id' => 'Company',
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
    $criteria->with = array('identity');
    $criteria->compare("LOWER(CONCAT(identity.last_name, ', ', identity.name))", strtolower($this->fullname_search), true);
    //$criteria->select = array('*', 'CONCAT(identity.last_name, ", ", identity.name) AS fullname');
		$criteria->compare('id',$this->id);
		$criteria->compare('identity_id',$this->identity_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->with = array('company');
		$criteria->compare('company.name', $this->company_name_search, true);
    /*$criteria->compare('identity.fullname', $this->fullname_search, true);*/
  		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IdentityCompany the static model class
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
