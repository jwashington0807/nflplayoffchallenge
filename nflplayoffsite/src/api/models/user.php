<?php

class User
{
	public $first;
	public $last;
	public $email;
	public $token;
	public $error;
}

class UserResponse
{
	public $email;
	public $error;
}

class Reset {
	public $email;
	public $password;
}

?>