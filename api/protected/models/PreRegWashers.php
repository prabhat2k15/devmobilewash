<?php

/**
 * This is the model class for table "pre_registered_washers".
 *

 */
class PreRegWashers extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pre_registered_washers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('first_name, last_name, email, phone, password, city, state, zipcode, hear_mw_how, van_lease, register_date, register_token, phone_verified', 'required'),
            array('id, first_name, last_name, email, phone, password, city, state, zipcode, hear_mw_how, van_lease, register_date, register_token, phone_verified', 'safe', 'on' => 'search'),
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'city' => 'City',
            'state' => 'State',
            'zipcode' => 'Zipcode',
            'hear_mw_how' => 'Hear MW How',
            'van_lease' => 'Van Lease',
            'register_date' => 'Register Date',
            'register_token' => 'Register Token',
            'phone_verified' => 'phone_verified'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('hear_mw_how', $this->hear_mw_how, true);
        $criteria->compare('van_lease', $this->van_lease, true);
        $criteria->compare('register_date', $this->register_date, true);
        $criteria->compare('register_token', $this->register_token, true);
        $criteria->compare('phone_verified', $this->phone_verified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
