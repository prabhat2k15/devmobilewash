<?php

/**
 * This is the model class for table "drivers".
 *
 * The followings are the available columns in table 'drivers':
 * @property integer $id
 * @property integer $customer_id
 * @property string $vehicle_no
 * @property string $brand_name
 * @property string $model_name
 * @property string $vehicle_image
 * @property string $vehicle_type
 * @property string $status
 * @property string $eco_friendly
 * @property string $damage_points
 * @property string $damage_pic
 */
class Vehicle extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'customer_vehicals';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('customer_id,', 'required'),
            array('id, customer_id, vehicle_no, brand_name, model_name, vehicle_image, vehicle_type, status, eco_friendly, damage_points, damage_pic, upgrade_pack, edit_vehicle, remove_vehicle_from_kart, new_vehicle_confirm, new_pack_name', 'safe', 'on' => 'search'),
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
            'customer_id' => 'Customer Id',
            'vehicle_id' => 'Vehicle id',
            'vehicle_no' => 'Vehicle no.',
            'brand_name' => 'Brand name',
            'model_name' => 'Model name',
            'vehicle_image' => 'Vehicle image',
            'vehicle_type' => 'Vehicle type',
            'status' => 'Status',
            'eco_friendly' => 'Eco Friendly',
            'damage_points' => 'Damage Points',
            'damage_pic' => 'Damage Pic',
            'upgrade_pack' => 'Upgrade Pack',
            'edit_vehicle' => 'Edit Vehicle',
            'remove_vehicle_from_kart' => 'Remove Vehicle From Kart',
            'new_vehicle_confirm' => 'New Vehicle Confirm',
            'new_pack_name' => 'New Pack Name'
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('customer_id', $this->customer_id, true);

        $criteria->compare('vehicle_no', $this->vehicle_no, true);
        $criteria->compare('brand_name', $this->brand_name, true);
        $criteria->compare('model_name', $this->model_name, true);
        $criteria->compare('vehicle_image', $this->vehicle_image, true);
        $criteria->compare('vehicle_type', $this->vehicle_type, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('eco_friendly', $this->eco_friendly, true);
        $criteria->compare('damage_points', $this->damage_points, true);
        $criteria->compare('damage_pic', $this->damage_pic, true);
        $criteria->compare('upgrade_pack', $this->upgrade_pack, true);
        $criteria->compare('edit_vehicle', $this->edit_vehicle, true);
        $criteria->compare('remove_vehicle_from_kart', $this->remove_vehicle_from_kart, true);
        $criteria->compare('new_vehicle_confirm', $this->new_vehicle_confirm, true);
        $criteria->compare('new_pack_name', $this->new_pack_name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
