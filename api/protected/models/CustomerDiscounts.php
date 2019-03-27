<?php

/**
 * This is the model class for table "customer_discounts".
 *
 * The followings are the available columns in table 'customer_discounts':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $wash_request_id
 * @property string $promo_code
 */
class CustomerDiscounts extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'customer_discounts';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('customer_id, wash_request_id, promo_code', 'required'),
            array('id, customer_id, wash_request_id, promo_code', 'safe', 'on' => 'search'),
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
            'wash_request_id' => 'Wash Request ID',
            'promo_code' => 'Promo Code'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('wash_request_id', $this->wash_request_id);
        $criteria->compare('promo_code', $this->promo_code);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
