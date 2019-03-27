<?php

/**
 * This is the model class for table "vip_coupon_codes".
 *

 */
class VipCouponCodes extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'vip_coupon_codes';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('package_name, deluxe_wash_limit, premium_wash_limit, dealer_name, package_code, dealer_code, unique_code, fullcode, customer_id, deluxe_wash_used, premium_wash_used', 'required'),
            array('id, package_name, deluxe_wash_limit, premium_wash_limit, dealer_name, package_code, dealer_code, unique_code, fullcode, customer_id, deluxe_wash_used, premium_wash_used', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'package_name' => 'Package Name',
            'deluxe_wash_limit' => 'Deluxe Wash Limit',
            'premium_wash_limit' => 'Premium Wash Limit',
            'dealer_name' => 'Dealer Name',
            'package_code' => 'Package Code',
            'dealer_code' => 'Dealer Code',
            'unique_code' => 'Unique Code',
            'fullcode' => 'Fullcode',
            'customer_id' => 'Customer id',
            'deluxe_wash_used' => 'Deluxe wash used',
            'premium_wash_used' => 'Premium wash used'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('package_name', $this->package_name, true);
        $criteria->compare('deluxe_wash_limit', $this->deluxe_wash_limit, true);
        $criteria->compare('premium_wash_limit', $this->premium_wash_limit, true);
        $criteria->compare('dealer_name', $this->dealer_name, true);
        $criteria->compare('package_code', $this->package_code, true);
        $criteria->compare('dealer_code', $this->dealer_code, true);
        $criteria->compare('unique_code', $this->unique_code, true);
        $criteria->compare('fullcode', $this->fullcode, true);
        $criteria->compare('customer_id', $this->customer_id, true);
        $criteria->compare('deluxe_wash_used', $this->deluxe_wash_used, true);
        $criteria->compare('premium_wash_used', $this->premium_wash_used, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
