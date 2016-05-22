<?php namespace App\Http\Controllers;

//use App\Http\Requests;
//use Illuminate\Http\Request;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


//Models
use App\User;
// Commands
use App\Commands\RegisterUserAssetCommand;
use App\Commands\LoginUserCommand;

class UserAssetsController extends ApiController {

	/**
	 * device info validation rules
	 * @var [type]
	 */
	protected $deviceValidationRules = [
		"imei"			=> 'required|numeric|digits_between:11,16|unique:devices',
		"model"			=> 'required|string',
		"type"			=> 'required|in:phone,modem',
		"serial"		=> 'required|string|max:17|unique:devices',
		"finger_print"	=> 'string|unique:devices',
		"manufacturer"	=> 'string',
		"os_version"	=> 'string',
		"bootloader"	=> 'string',
		"build_id"		=> 'string'
	];
	/**
	 * sim info validation rules
	 * @var [type]
	 */
	protected $simValidationRules = [
		"imsi" 				=> 'required|numeric|digits_between:11,16|unique:sims',
		"number"			=> 'required|numeric|digits_between:10,13|unique:phnumbers,msisdn',
		"operatorNumber"	=> 'required|string|min:3|exists:operators,code'
	]; 
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($user_id)
	{
		// if(LoginUserCommand::isLogged($user_id))
		// {
			try{

				$deviceValidator = Validator::make(Request::input("device"), $this->deviceValidationRules);
				$simValidator 	 = Validator::make(Request::input("sim"), $this->simValidationRules);
				if($deviceValidator->fails() || $simValidator->fails())
				{
			 		$errors = array_merge(array_flatten($deviceValidator->messages()->toArray()), array_flatten($simValidator->messages()->toArray()));
			 		return $this->respondNotAcceptable($errors);
				} 
				// register user's asset using RegisterUserAssetCommand
				$asset = RegisterUserAssetCommand::fromRequest($user_id, Request::all());

				return $this->respondAccepted(["assetId" => $asset]);	
			}
			catch(\ErrorException $e)
			{
				return $this->respondNotAcceptable(["Unknow Error during processing",$e->getMessage()]);
			}
		// }
		// return $this->respondNotAuthenticated(["user is not authenticated"]);

	}
}
