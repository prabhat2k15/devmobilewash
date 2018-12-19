<?php

/**
 * This is the model class for table "pre_registered_clients".
 *

 */
class PreRegClients extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pre_registered_clients';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('first_name, last_name, email, phone, city, state, how_hear_mw, register_date,zipcode,address,source', 'required'),
            array('id, first_name, last_name, email, phone, city, state, how_hear_mw, register_date', 'safe', 'on' => 'search'),
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
            'city' => 'City',
            'state' => 'State',
            'how_hear_mw' => 'How hear mw',
            'register_date' => 'Register Date'
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
        $criteria->compare('city', $this->city, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('how_hear_mw', $this->how_hear_mw, true);
        $criteria->compare('register_date', $this->register_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
