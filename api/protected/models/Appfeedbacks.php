<?php

/**
 * This is the model class for table "app_feedbacks".
 *
 * The followings are the available columns in table 'app_feedbacks':
 * @property integer $id
 * @property integer $customer_id
 * @property string $feedback

 */
 
class Appfeedbacks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'app_feedbacks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('customer_id, feedback', 'required'),
			array('id, customer_id, feedback', 'safe', 'on'=>'search'),
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
			'customer_id' => 'Customer ID',
			'feedback' => 'Feedback'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('feedback',$this->feedback,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}