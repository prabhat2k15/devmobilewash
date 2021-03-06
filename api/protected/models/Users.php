<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $device_token
 */
class Users extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, username, password, users_type, device_token, password_reset_token, last_login_at, last_login_data, verify_code', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, email, username, password, users_type, device_token, last_login_at, last_login_data, verify_code', 'safe', 'on' => 'search'),
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
            'email' => 'Email',
            'username' => 'Username',
            'password' => 'Password',
            'users_type' => 'Users Type',
            'device_token' => 'Device Token',
            'password_reset_token' => 'password reset token',
            'last_login_at' => 'last login at',
            'last_login_data' => 'last login data',
            'verify_code' => 'verify code',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('users_type', $this->users_type, true);
        $criteria->compare('device_token', $this->device_token, true);
        $criteria->compare('password_reset_token', $this->password_reset_token, true);
        $criteria->compare('last_login_at', $this->last_login_at, true);
        $criteria->compare('last_login_data', $this->last_login_data, true);
        $criteria->compare('verify_code', $this->verify_code, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
