<?php

/**
 * This is the model class for table "wash_pricing_history".
 *

 */
class WashPricingHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wash_pricing_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('wash_request_id, vehicle_id, package, vehicle_price, pet_hair, lifted_vehicle, exthandwax_addon, extplasticdressing_addon, extclaybar_addon, waterspotremove_addon, safe_handling, bundle_disc, last_updated', 'required'),
			array('wash_request_id, vehicle_id, package, vehicle_price, pet_hair, lifted_vehicle, exthandwax_addon, extplasticdressing_addon, extclaybar_addon, waterspotremove_addon, safe_handling, bundle_disc, last_updated', 'safe', 'on'=>'search'),
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
			'wash_request_id' => 'wash request id',
			'vehicle_id' => 'vehicle id',
			'package' => 'package',
'vehicle_price' => 'vehicle price',
			'pet_hair'=>'pet hair',
			'lifted_vehicle' => 'lifted vehicle',
'exthandwax_addon' => 'exthandwax addon',
'extplasticdressing_addon' => 'extplasticdressing addon',
'extclaybar_addon' => 'extclaybar addon',
'waterspotremove_addon' => 'waterspotremove addon',
'safe_handling' => 'safe handling',
'bundle_disc' => 'bundle disc',
'last_updated' => 'last updated'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('wash_request_id',$this->wash_request_id,true);
		$criteria->compare('vehicle_id',$this->vehicle_id,true);
		$criteria->compare('package',$this->package,true);
$criteria->compare('vehicle_price',$this->vehicle_price,true);
		$criteria->compare('pet_hair',$this->pet_hair,true);
		$criteria->compare('lifted_vehicle',$this->lifted_vehicle,true);
$criteria->compare('exthandwax_addon',$this->exthandwax_addon,true);
$criteria->compare('extplasticdressing_addon',$this->extplasticdressing_addon,true);
$criteria->compare('extclaybar_addon',$this->extclaybar_addon,true);
$criteria->compare('waterspotremove_addon',$this->waterspotremove_addon,true);
$criteria->compare('safe_handling',$this->safe_handling,true);
$criteria->compare('bundle_disc',$this->bundle_disc,true);
$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}