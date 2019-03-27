<?php

/**
 * This is the model class for table "coverage_area_zipcodes".
 *

 */
class CoverageAreaCodes extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'coverage_area_zipcodes';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('zipcode, zip_color', 'required'),
            array('id, zipcode, zip_color', 'safe', 'on' => 'search'),
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
            'zipcode' => 'Zipcode',
            'zip_color' => 'Zipcolor'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('zip_color', $this->zip_color, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
