<?php

/**
 * This is the model class for table "washing_plans".
 *
 * The followings are the available columns in table 'washing_plans':
 * @property integer $id
 * @property string $title
 * @property string $duration
 * @property decimal $price
 * @property integer $handling_fee
 * @property string $description
 */
class Washingplans extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'washing_plans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title,', 'required'),
			array('id, title, duration, price, handling_fee, description', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'duration' => 'Duration',
			'price' => 'Price',
			'handling_fee'=>'Handling fee',
			'description' => 'Description'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('duration',$this->duration,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('handling_fee',$this->handling_fee,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
