<?php

class Customers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, customername, password, image, device_token, mobile_type, time_zone, contact_number, email_alerts, push_notifications, total_wash, login_type, account_status, online_status, created_date, updated_date, client_position, how_hear_mw, form_tracker', 'required'),
			array('account_status', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>100),
			array('customername', 'length', 'max'=>150),
			array('password', 'length', 'max'=>32),
			array('image, device_token', 'length', 'max'=>255),
			array('mobile_type', 'length', 'max'=>50),
			array('login_type', 'length', 'max'=>10),
			array('token, created_date, updated_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, email, customername, password, image, device_token, mobile_type, contact_number, email_alerts, push_notifications, total_wash, account_status, online_status, created_date, updated_date, client_position, how_hear_mw, form_tracker, is_schedule_popup_shown', 'safe', 'on'=>'search'),
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
			'email' => 'Email',
			'customername' => 'Username',
			'password' => 'Password',
			'image' => 'Image',
			'device_token' => 'Device Token',
			'contact_number'=>'Contact Number',
			'email_alerts'=>'Email Alerts',
			'push_notifications'=>'Push Notifications',
			'total_wash'=>'Total Wash',
			'mobile_type' => 'Mobile Type',
			'account_status' => 'Account Status',
            'online_status' => 'Online Status',
			'created_date' => 'Created Date',
			'updated_date' => 'Updated Date',
			'client_position' => 'client position',
			'how_hear_mw' => 'how hear mw',
			'form_tracker' => 'registered from',
			'is_schedule_popup_shown' => 'is_schedule_popup_shown'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('customername',$this->customername,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('device_token',$this->device_token,true);
		$criteria->compare('mobile_type',$this->mobile_type,true);
		$criteria->compare('contact_number',$this->contact_number,true);
		$criteria->compare('email_alerts',$this->email_alerts,true);
		$criteria->compare('push_notifications',$this->push_notifications,true);
		$criteria->compare('total_wash',$this->total_wash,true);
		$criteria->compare('account_status',$this->account_status);
        $criteria->compare('online_status',$this->online_status);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('client_position',$this->client_position,true);
		$criteria->compare('how_hear_mw',$this->how_hear_mw,true);
		$criteria->compare('form_tracker',$this->form_tracker,true);
		$criteria->compare('is_schedule_popup_shown',$this->is_schedule_popup_shown,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Customers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}