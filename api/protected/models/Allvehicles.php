<?php

/**
 * This is the model class for table "all_vehicles".
 *
 * The followings are the available columns in table 'all_vehicles':
 * @property integer $id
 * @property string $make
 * @property string $model
 * @property string $type

 */
class Allvehicles extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'all_vehicles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('model,', 'required'),
            array('id, make, model, type', 'safe', 'on' => 'search'),
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
            'make' => 'Make',
            'model' => 'Model',
            'type' => 'Type'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('make', $this->make, true);
        $criteria->compare('model', $this->model, true);
        $criteria->compare('type', $this->type, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
