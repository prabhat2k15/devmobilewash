<?php

/**
 * This is the model class for table "drivers".
 *
 * The followings are the available columns in table 'drivers':
 * @property integer $id
 * @property integer $customer_id
 * @property string $vehicle_no
 * @property string $brand_name
 * @property string $model_name
 * @property string $vehicle_image
 * @property string $vehicle_type
  * @property string $status
  * @property string $eco_friendly
  * @property string $damage_points
  * @property string $damage_pic
 */
class CustomerDraftVehicle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'draft_customer_vehicals';
	}

	/**
	 * @return array validation rules for model attributes.
	 */

	public function rules()
	{
		return array(
			array('customer_id,', 'required'),
			array('id, customer_id, vehicle_no, brand_name, model_name, vehicle_image, vehicle_type', 'safe', 'on'=>'search'),
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
			'vehicle_id' => 'Vehicle id',
			'vehicle_no' => 'Vehicle no.',
			'brand_name' => 'Brand name',
			'model_name' => 'Model name',
			'vehicle_image'=>'Vehicle image',
			'vehicle_type' => 'Vehicle type'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id,true);

		$criteria->compare('vehicle_no',$this->vehicle_no,true);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('vehicle_image',$this->vehicle_image, true);
		$criteria->compare('vehicle_type',$this->vehicle_type, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}