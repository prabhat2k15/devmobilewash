<?php

/**
 * This is the model class for table "customer_schedule_info".
 *
 * The followings are the available columns in table 'customer_schedule_info':
 * @property integer $id
 * @property string $customer_id
 * @property string $latitude
 * @property string $longitude
 */
class CustomerScheduleInfo extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'customer_schedule_info';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('customer_id, zipcode, address, address_type, cardno, cvc, mo_exp, yr_exp', 'required'),
            array('id, customer_id, zipcode, address, address_type, cardno, cvc, mo_exp, yr_exp', 'safe', 'on' => 'search'),
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
            'customer_id' => 'Customer ID',
            'zipcode' => 'Zipcode',
            'address' => 'Address',
            'address_type' => 'Address Type',
            'cardno' => 'Card No',
            'cvc' => 'CVC',
            'mo_exp' => 'Month Expire',
            'yr_exp' => 'Year Expire',
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('customer_id', $this->customer_id, true);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('address_type', $this->address_type, true);
        $criteria->compare('cardno', $this->cardno, true);
        $criteria->compare('cvc', $this->cvc, true);
        $criteria->compare('mo_exp', $this->mo_exp, true);
        $criteria->compare('yr_exp', $this->yr_exp, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
