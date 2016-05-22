<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\Phnumber;
use App\Complain;

class CreateComplain extends Command implements SelfHandling {
public $complain;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	private function __construct()
	{
		
	}

	public static function storeComplain($data)
	{
		$command=new CreateComplain();
		$mobile_number=$data->input('prometical_dial');     
		$user_id=\Auth::user()->id;
		$asset_id=\Auth::user()->assets[0]->id;
		$array=['user_id'=>$user_id,'asset_id'=>$asset_id];
		$data->merge($array);
	
		
		
		$command->complain=Complain::firstOrCreate($data->except('_token','radio'));

	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		return $this->complain;
	}

}
