<?php

/**
 * This is the model class for table "drivers".
 *
 * The followings are the available columns in table 'drivers':
 * @property integer $id
 * @property string $customer_id
 * @property string $vehicle_id
 * @property string $brand_name
 * @property string $model_name
 * @property string $vehicle_image
 * @property string $vehicle_type
 */
class CustomerLocation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer_locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('customer_id, location_title, location_address, city, state, zipcode, actual_longitude, actual_latitude', 'required'),
			array('id, customer_id, location_title, location_address, city, state, zipcode, actual_latitude, actual_longitude', 'safe', 'on'=>'search'),
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
			'customer_id' => 'Customer Id',
			'location_id' => 'location_id',
			'location_title' => 'location_title',
			'location_address' => 'location_address',
			'city' => 'city',
			'state' => 'state',
			'zipcode' => 'zipcode',
			'actual_longitude'=>'actual_longitude',
			'actual_latitude' => 'Actual latitude'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('location_id',$this->location_id,true);
		$criteria->compare('location_title',$this->location_title,true);
		$criteria->compare('location_address',$this->location_address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('actual_longitude',$this->actual_longitude,true);
		$criteria->compare('vehicle_type',$this->vehicle_type);
		$criteria->compare('actual_latitude',$this->actual_latitude);
		$criteria->compare('core_location_id',$this->core_location_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}