<?php

/**
 * This is the model class for table "coupon_codes".
 *

 */
class CouponCodes extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'coupon_codes';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('coupon_name, coupon_code, express_amount, deluxe_amount, premium_amount, discount_unit, coupon_status, usage_limit, expire_date', 'required'),
            array('id, coupon_name, coupon_code, express_amount, deluxe_amount, premium_amount, discount_unit, coupon_status, usage_limit, expire_date', 'safe', 'on' => 'search'),
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
            'coupon_name' => 'Coupon Name',
            'coupon_code' => 'Coupon Code',
            'express_amount' => 'Express Amount',
            'deluxe_amount' => 'Deluxe Amount',
            'premium_amount' => 'Premium Amount',
            'discount_unit' => 'Discount Unit',
            'coupon_status' => 'Coupon Status',
            'usage_limit' => 'Usage Limit',
            'expire_date' => 'Expire Date'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('coupon_name', $this->coupon_name, true);
        $criteria->compare('coupon_code', $this->coupon_code, true);
        $criteria->compare('express_amount', $this->express_amount, true);
        $criteria->compare('deluxe_amount', $this->deluxe_amount, true);
        $criteria->compare('premium_amount', $this->premium_amount, true);
        $criteria->compare('discount_unit', $this->discount_unit, true);
        $criteria->compare('coupon_status', $this->coupon_status, true);
        $criteria->compare('usage_limit', $this->usage_limit, true);
        $criteria->compare('expire_date', $this->expire_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
