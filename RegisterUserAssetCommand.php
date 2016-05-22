<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use App\Asset as UserAsset;
use App\Device;
use App\Sim;
use App\Phnumber;
use App\Operator;

class RegisterUserAssetCommand extends Command implements SelfHandling {

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
	 * Create new asset from json array
	 * @return [object] [command handler]
	 */
	public static function fromRequest($user_id , $input)
	{
		$command = new RegisterUserAssetCommand;

		$device = Device::firstOrCreate($input['device']);
					
		// will save the sim anyway too
		$sim =  Sim::firstOrCreate(['imsi' => $input['sim']['imsi']]);

		$operator = Operator::whereCode($input["sim"]["operatorNumber"])->first();
		
		// assume that number of regisration is valid and belongs to sim card
		$phnumber = Phnumber::firstOrCreate(['msisdn'=> $input["user"]["mobile"], "sim_id" => $sim->id, "operator_id"=>$operator->id]);

		$command->asset  = UserAsset::firstOrCreate(["user_id"=>$user_id, "device_id"=>$device->id,"sim_id"=>$sim->id]);
		
		return $command->handle();
	}
	/**
	 * Execute the command.
	 *
	 * @return [integer] [Asset id]
	 */
	public function handle()
	{
		return $this->asset->id;
	}

}
