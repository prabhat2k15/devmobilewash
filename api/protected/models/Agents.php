<?php

class Agents extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'agents';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('first_name, last_name, email, password, date_of_birth, device_token, mobile_type, phone_number, street_address, suite_apt, city, state, zipcode, driver_license, proof_insurance, business_license, bank_account_number, routing_number, legally_eligible, own_vehicle, waterless_wash_product, operate_area, work_schedule, operating_as, company_name, wash_experience, image, email_alerts, push_notifications, agent_location, token, bt_submerchant_id, status, account_status, total_wash, created_date, updated_date, available_for_new_order, washer_position, real_washer_id', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, first_name, last_name, email, password, date_of_birth, device_token, mobile_type, phone_number, street_address, suite_apt, city, state, zipcode, driver_license, proof_insurance, business_license, bank_account_number, routing_number, legally_eligible, own_vehicle, waterless_wash_product, operate_area, work_schedule, operating_as, company_name, wash_experience, image, email_alerts, push_notifications, agent_location, token, bt_submerchant_id, status, account_status, total_wash, created_date, updated_date, available_for_new_order, washer_position, real_washer_id', 'safe', 'on' => 'search'),
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
            'agentname' => 'agentname',
            'email' => 'Email',
            'password' => 'Password',
            'date_of_birth' => 'Date of Birth',
            'device_token' => 'Device Token',
            'mobile_type' => 'Mobile Type',
            'phone_number' => 'Phone Number',
            'street_address' => 'Street Address',
            'suite_apt' => 'Suite/Apt',
            'city' => 'City',
            'state' => 'State',
            'zipcode' => 'Zipcode',
            'driver_license' => 'Driver License',
            'proof_insurance' => 'Proof of Insurance',
            'business_license' => 'Business License',
            'bank_account_number' => 'Bank Account Number',
            'routing_number' => 'routing_number',
            'operating_as' => 'Are you operating as business or individual',
            'legally_eligible' => 'Are you legally eligible to work in the U.S.?',
            'own_vehicle' => 'Do you own a vehicle and mobile auto detail equipment?',
            'waterless_wash_product' => 'Do you have waterless car wash products?',
            'operate_area' => 'what areas would you prefer to operate in?',
            'work_schedule' => 'what is your preferred work schedule?',
            'company_name' => 'Company Name',
            'wash_experience' => 'Years of wash experience',
            'image' => 'Image',
            'email_alerts' => 'email_alerts',
            'push_notifications' => 'Push Notifications',
            'agent_location' => 'Agent Location',
            'token' => 'Token',
            'bt_submerchant_id' => 'Braintree Submerchant ID',
            'status' => 'Status',
            'account_status' => 'Account Status',
            'total_wash' => 'Total Wash',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'available_for_new_order' => 'Available for new order',
            'washer_position' => 'washer position',
            'real_washer_id' => 'real washer id',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('date_of_birth', $this->date_of_birth, true);
        $criteria->compare('device_token', $this->device_token, true);
        $criteria->compare('mobile_type', $this->mobile_type, true);
        $criteria->compare('phone_number', $this->phone_number, true);
        $criteria->compare('street_address', $this->street_address, true);
        $criteria->compare('suite_apt', $this->suite_apt, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('driver_license', $this->driver_license, true);
        $criteria->compare('proof_insurance', $this->proof_insurance, true);
        $criteria->compare('business_license', $this->business_license, true);
        $criteria->compare('bank_account_number', $this->bank_account_number, true);
        $criteria->compare('routing_number', $this->routing_number, true);
        $criteria->compare('operating_as', $this->operating_as, true);
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('wash_experience', $this->wash_experience, true);
        $criteria->compare('legally_eligible', $this->legally_eligible, true);
        $criteria->compare('own_vehicle', $this->own_vehicle, true);
        $criteria->compare('waterless_wash_product', $this->waterless_wash_product, true);
        $criteria->compare('operate_area', $this->operate_area, true);
        $criteria->compare('work_schedule', $this->work_schedule, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('email_alerts', $this->email_alerts, true);
        $criteria->compare('push_notifications', $this->push_notifications, true);
        $criteria->compare('agent_location', $this->agent_location);
        $criteria->compare('token', $this->token);
        $criteria->compare('bt_submerchant_id', $this->bt_submerchant_id);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('account_status', $this->account_status, true);
        $criteria->compare('total_wash', $this->total_wash, true);
        $criteria->compare('created_date', $this->created_date, true);
        $criteria->compare('updated_date', $this->updated_date, true);
        $criteria->compare('available_for_new_order', $this->available_for_new_order, true);
        $criteria->compare('washer_position', $this->washer_position, true);
        $criteria->compare('real_washer_id', $this->real_washer_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Customers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
