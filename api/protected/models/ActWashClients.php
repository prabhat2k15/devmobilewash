<?php

/**
 * This is the model class for table "pre_registered_clients".
 *

 */
class ActWashClients extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'active_washers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('user_id, user_email, first_name, last_name, phone, address, city, state, zip, ID_number, DL_ID_exp, insurance_exp, payment_due_d_ins, account_name, SSN_ITIN_TAX_ID, routing_number, account_number', 'required'),
            array('id, user_id, user_email, first_name, last_name, phone, address, city, state, zip, ID_number, DL_ID_exp, insurance_exp, payment_due_d_ins, account_name, SSN_ITIN_TAX_ID, routing_number, account_number', 'safe', 'on' => 'search'),
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
            'user_id' => 'User ID',
            'user_email' => 'User Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'ID_number' => 'ID Number',
            'DL_ID_exp' => 'DL/ID Expire',
            'insurance_exp' => 'Insurance Exp',
            'payment_due_d_ins' => 'Payment Due Date Ins',
            'account_name' => 'Account Name',
            'SSN_ITIN_TAX_ID' => 'SSN ITIN TAX ID',
            'routing_number' => 'Routing Number',
            'account_number' => 'Account Number'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('user_email', $this->user_email, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('zip', $this->zip, true);
        $criteria->compare('ID_number', $this->ID_number, true);
        $criteria->compare('DL_ID_exp', $this->DL_ID_exp, true);
        $criteria->compare('insurance_exp', $this->insurance_exp, true);
        $criteria->compare('payment_due_d_ins', $this->payment_due_d_ins, true);
        $criteria->compare('account_name', $this->account_name, true);
        $criteria->compare('SSN_ITIN_TAX_ID', $this->SSN_ITIN_TAX_ID, true);
        $criteria->compare('routing_number', $this->routing_number, true);
        $criteria->compare('account_number', $this->account_number, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
