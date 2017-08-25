<?php

/**
 * This is the model class for table "washing_requests".
 *
 * The followings are the available columns in table 'washing_requests':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $agent_id
 * @property string $car_list
 * @property string $package_list
 * @property string $address
 * @property string $address_type
 * @property string $latitude
 * @property string $longitude
 * @property string $payment_type
 * @property string $nonce
  * @property string $estimate_time
    * @property string $status
     * @property string $created_date
 */
class Washingrequests extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'washing_requests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('customer_id, agent_id, car_list, package_list, address, address_type, latitude, longitude, payment_type, nonce, estimate_time, status, created_date, is_scheduled, transaction_id, order_for', 'required'),
			array('id, customer_id, agent_id, car_list, package_list, address, address_type, latitude, longitude, payment_type, nonce, estimate_time, status, is_scheduled, created_date', 'safe', 'on'=>'search'),
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
			'customer_id' => 'Customer ID',
			'agent_id' => 'Agent ID',
			'car_list' => 'Car List',
			'package_list' => 'Package List',
			'address'=>'Address',
			'address_type' => 'Address Type',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'payment_type' => 'Payment Type',
			'nonce' => 'Nonce',
			'estimate_time' => 'Estimate Time',
'is_scheduled' => 'is scheduled',
			'status' => 'Status',
			'created_date' => 'Created Date',
			'order_for' => 'Order For'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('agent_id',$this->agent_id,true);
		$criteria->compare('car_list',$this->car_list,true);
		$criteria->compare('package_list',$this->package_list,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address_type',$this->address_type,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('payment_type',$this->payment_type,true);
		$criteria->compare('nonce',$this->nonce,true);
		$criteria->compare('estimate_time',$this->estimate_time,true);
$criteria->compare('is_scheduled',$this->is_scheduled,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_date',$this->created_date,true);
			$criteria->compare('order_for',$this->order_for,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}