<?php

/**
 * This is the model class for table "agent_locations".
 *
 * The followings are the available columns in table 'agent_locations':
 * @property integer $id
 * @property string $agent_id
 * @property string $latitude
 * @property string $longitude
 */
class AgentLocations extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'agent_locations';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('agent_id, latitude, longitude', 'required'),
            array('id, agent_id, latitude, longitude', 'safe', 'on' => 'search'),
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
            'agent_id' => 'Agent ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('agent_id', $this->agent_id, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longitude', $this->longitude, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
