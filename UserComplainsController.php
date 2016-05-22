<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\ApiController;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\ComplainRequest;
use App\Commands\CreateComplainCommand;
class UserComplainsController extends ApiController {
	/**
	 * [$complainValidationRules description]
	 * this is loosy validation rule
	 * user_id must be attached to asset_id
	 * @var Array
	 */
	protected $complainValidationRules = [
		"assetId" => "required|numeric|exists:assets,id",
		"userId"  => "required|exists:assets,user_id"
	];
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($user_id)
	{
		$request = array_add(Request::all(),'userId',$user_id);
		$validator = Validator::make($request, $this->complainValidationRules);

		if($validator->fails())
		{

			return $this->respondNotAcceptable(array_flatten($validator->messages()->toArray()));
		}
		try{
			CreateComplainCommand::fromJson($request);
			return $this->respondCreated();
		}catch(\Exception $e)
		{
			return $this->respondWithError(["Unknown error during processing", $e->getMessage()]);
		}
	}
}
