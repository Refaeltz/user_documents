<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 03/08/2019
 * Time: 15:15
 */
namespace App\Manager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UserManager
{
	public static $instance;
	private static $PREFIX = "ewave_";
	private static $SUFFIX = "_exercise";
	public $user_id;
	public $user_name;

	/**
	 * UserManager constructor.
	 * @param $user_id
	 */
	public function __construct($user_id = 0, $user_name = '')
	{
		$session = new Session();

		$this->user_id = $user_id;
		$this->user_name = $user_name;

		if(!!$this->user_id)
		{
			$session->set('user_id', $this->user_id);
			$session->set('user_name', $this->user_name);
		}
		else{
			$this->user_id = $session->get('user_id');
			$this->user_name = $session->get('user_name');
		}
	}

	public static function logoutUser(){
		$session = new Session();
		$session->clear();
	}

	public static function encryptPassword($password){
		return md5(self::$PREFIX.$password.self::$SUFFIX);
	}
}