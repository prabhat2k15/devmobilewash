<?php

/**
 * This is the model class for table "washing_inspection_details".
 *
 * The followings are the available columns in table 'washing_inspection_details':
 * @property integer $id
 * @property integer $wash_request_id
 * @property integer $vehicle_id
 * @property string $damage_pic
 */
class Washinginspections extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'washing_inspection_details';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('wash_request_id, vehicle_id, damage_pic, eco_friendly', 'required'),
            array('id, wash_request_id, vehicle_id, damage_pic, eco_friendly', 'safe', 'on' => 'search'),
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
            'wash_request_id' => 'Wash Request Id',
            'vehicle_id' => 'vehicle id',
            'damage_pic' => 'Damage Pic',
            'eco_friendly' => 'Eco Friendly'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('wash_request_id', $this->wash_request_id, true);
        $criteria->compare('vehicle_id', $this->vehicle_id, true);
        $criteria->compare('damage_pic', $this->damage_pic, true);
        $criteria->compare('eco_friendly', $this->eco_friendly, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
