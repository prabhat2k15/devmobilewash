<?php

/**
 * This is the model class for table "drivers".
 *
 * The followings are the available columns in table 'drivers':
 * @property integer $id
 * @property integer $wash_request_id
 * @property string $route
 */
class WashReqRoute extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'wash_req_route';
    }

    /**
     * @return array validation rules for model attributes.
     */

    public function rules()
    {
        return array(
            array('id, wash_request_id, route', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'wash_request_id' => 'Wash Request ID',
            'rotue' => 'Route'
        );
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('wash_request_id',$this->wash_request_id);
        $criteria->compare('rotue',$this->rotue,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}