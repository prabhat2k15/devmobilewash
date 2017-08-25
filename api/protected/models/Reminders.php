<?php

/**
 * This is the model class for table "reminders".
 *

 */
class Reminders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reminders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('client_reminder_titles, client_reminder_contents, washer_reminder_titles, washer_reminder_contents', 'required'),
			array('client_reminder_titles, client_reminder_contents, washer_reminder_titles, washer_reminder_contents', 'safe', 'on'=>'search'),
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
		
			'client_reminder_titles' => 'Client Reminder Titles',
'client_reminder_contents' => 'Client Reminder Contents',
'washer_reminder_titles' => 'Washer Reminder Titles',
'washer_reminder_contents' => 'Washer Reminder Contents'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('client_reminder_titles',$this->client_reminder_titles,true);
		$criteria->compare('client_reminder_contents',$this->client_reminder_contents,true);
$criteria->compare('washer_reminder_titles',$this->washer_reminder_titles,true);
		$criteria->compare('washer_reminder_contents',$this->washer_reminder_contents,true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}