<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    public $username;
    public $password;
    public $user_type2;

    public function __construct($username = '', $password = '', $user_type = '') {
        $this->username = $username;
        $this->password = $password;
        $this->user_type2 = $user_type;
    }

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {

        $user_exists = Users::model()->findByAttributes(array('username' => $this->username, 'password' => md5($this->password), 'users_type' => $this->user_type2));

        if (count($user_exists) > 0)
            $this->errorCode = self::ERROR_NONE;
        else
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        return !$this->errorCode;
    }

}
