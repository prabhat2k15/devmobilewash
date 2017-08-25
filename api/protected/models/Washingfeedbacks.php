<?php

/**
 * This is the model class for table "washing_feedbacks".
 *
 * The followings are the available columns in table 'washing_feedbacks':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $agent_id
  * @property integer $wash_request_id
 * @property string $customer_comments
 * @property string $customer_ratings
 * @property string $agent_comments
 * @property string $agent_ratings

 */
class Washingfeedbacks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'washing_feedbacks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('wash_request_id,', 'required'),
			array('id, customer_id, agent_id, wash_request_id, customer_comments, customer_ratings, agent_comments, agent_ratings', 'safe', 'on'=>'search'),
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
			'agent_id' => 'Agent ID',
			'wash_request_id' => 'Wash Request ID',
			'customer_comments' => 'Customer Comments',
			'customer_ratings'=>'Customer Ratings',
			'agent_comments' => 'Agent Comments',
			'agent_ratings'=>'Agent Ratings'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('agent_id',$this->agent_id,true);
		$criteria->compare('wash_request_id',$this->wash_request_id,true);
		$criteria->compare('customer_comments',$this->customer_comments,true);
		$criteria->compare('customer_ratings',$this->customer_ratings,true);
		$criteria->compare('agent_comments',$this->agent_comments,true);
		$criteria->compare('agent_ratings',$this->agent_ratings,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}