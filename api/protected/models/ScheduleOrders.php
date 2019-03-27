<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $device_token
 */
class ScheduleOrders extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'schedule_orders';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, name, email, phone, address, address_type, city, zipcode, schedule_date, schedule_time, how_hear_mw, vehicles, total_price, transaction_id, created_date, card_type, card_ending_no', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, customer_id, name, email, phone, address, address_type, city, zipcode, schedule_date, schedule_time, how_hear_mw, vehicles, total_price, transaction_id, created_date, card_type, card_ending_no', 'safe', 'on' => 'search'),
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
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'address_type' => 'Address type',
            'city' => 'city',
            'zipcode' => 'Zipcode',
            'schedule_date' => 'Schedule date',
            'schedule_time' => 'Schedule time',
            'how_hear_mw' => 'How hear mw',
            'vehicles' => 'Vehicles',
            'total_price' => 'Total Price',
            'transaction_id' => 'Transaction id',
            'created_date' => 'Created date',
            'card_type' => 'Card type',
            'card_ending_no' => 'Card ending no'
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
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('name', $this->name);
        $criteria->compare('email', $this->email);
        $criteria->compare('phone', $this->phone);
        $criteria->compare('address', $this->address);
        $criteria->compare('address_type', $this->address_type);
        $criteria->compare('city', $this->city);
        $criteria->compare('zipcode', $this->zipcode);
        $criteria->compare('schedule_date', $this->schedule_date);
        $criteria->compare('schedule_time', $this->schedule_time);
        $criteria->compare('how_hear_mw', $this->how_hear_mw);
        $criteria->compare('vehicles', $this->vehicles);
        $criteria->compare('total_price', $this->total_price);
        $criteria->compare('transaction_id', $this->transaction_id);
        $criteria->compare('created_date', $this->created_date);
        $criteria->compare('card_type', $this->card_type);
        $criteria->compare('card_ending_no', $this->card_ending_no);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
