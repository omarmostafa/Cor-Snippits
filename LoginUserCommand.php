<?php namespace App\Commands;

use App\Commands\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginUserCommand extends Command{

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	private function __construct()
	{
		// named constructor style
	}

	/**
	 * login user with basic auth using email and passwod
	 * @param  [string] $email    
	 * @param  [string] $password 
	 */
	public static function byEmailAndPassword($email, $password)
	{
		$command = new LoginUserCommand;
		$credentials=compact("email","password");
		if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            else
            {
            return $token;
        }
	}
	/**
	 * login user with basic auth using id
	 * @param  [string] $id 
	 */
	public static function byId($id)
	{
		$command = new LoginUserCommand;

		Auth::loginUsingId($id);
	}
	/**
	 * login user by user instance
	 * @param  User   $user [user object]
	 */
	public static function byUser(User $user)
	{
		$command = new LoginUserCommand;

		$command->user = $user;

		Auth::login($command->user);

	}
	/**
	 * Check if given user id is logged
	 * @param  [string]  $id [user's id]
	 * @return [boolean]     
	 */
	public static function isLogged($id)
	{
		if(Auth::check() && Auth::user()->id == $id)
			return true;
		else
			return false;
	}

}
