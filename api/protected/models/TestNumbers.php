<?php

/**
 * This is the model class for table "site sttings".
 *
 * The followings are the available columns in table 'site sttings':

 */
class TestNumbers extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'test_numbers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('id,phone', 'required'),
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
            'id' => 'id',
            'phone' => 'Phone',
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
