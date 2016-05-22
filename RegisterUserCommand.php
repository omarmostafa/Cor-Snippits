<?php namespace App\Commands;

use App\Commands\Command;
use App\User;

class RegisterUserCommand extends Command {
	
	/**
	 * user data 
	 * @var [array]
	 */
	public $user;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	private function __construct()
	{
		// named constructor style 	
	}

	public static function fromJson(Array $user)
	{
		$command = new RegisterUserCommand();
		$command->user = $user;
		return $command;
	}

	public function user()
	{
		return $this->user;
	}



}
