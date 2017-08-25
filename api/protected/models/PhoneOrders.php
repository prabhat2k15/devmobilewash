<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $password
  * @property string $device_token
 */
class PhoneOrders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'phone_orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customername, phoneno, address, address_type, city, schedule_date, schedule_time, email, how_hear_mw, regular_vehicles, classic_vehicles, created_date, total_price, agent_total, company_total', 'required'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customername, phoneno, address, address_type, city, schedule_date, schedule_time, email, how_hear_mw, regular_vehicles, classic_vehicles, created_date, total_price, agent_total, company_total', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'customername' => 'customername',
		'phoneno' => 'phoneno',	
'address' => 'address',	
'address_type' => 'address type',	
'city' => 'city',	
'schedule_date' => 'schedule_date',
'schedule_time' => 'schedule_time',
'email' => 'email',
'how_hear_mw' => 'how_hear_mw',
'regular_vehicles' => 'regular_vehicles',
'classic_vehicles' => 'classic_vehicles',
'created_date' => 'created_date',
'total_price' => 'total_price',
'agent_total' => 'agent_total',
'company_total' => 'company_total'
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
      $criteria->compare('customername',$this->customername);
	$criteria->compare('phoneno',$this->phoneno);	
$criteria->compare('address',$this->address);
$criteria->compare('address_type',$this->address_type);		
$criteria->compare('city',$this->city);
$criteria->compare('schedule_date',$this->schedule_date);
$criteria->compare('schedule_time',$this->schedule_time);
$criteria->compare('email',$this->email);
$criteria->compare('how_hear_mw',$this->how_hear_mw);
$criteria->compare('regular_vehicles',$this->regular_vehicles);
$criteria->compare('classic_vehicles',$this->classic_vehicles);
$criteria->compare('created_date',$this->created_date);
$criteria->compare('total_price',$this->total_price);
$criteria->compare('agent_total',$this->agent_total);
$criteria->compare('company_total',$this->company_total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}