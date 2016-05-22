<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
class ProcessUserCommand extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public $Users;
	private function __construct()
	{
		
	}

	public static function Process($data)
	{
		$command=new ProcessUserCommand();
		if($data->has('delete'))
		{
			$command->delete($data);
		}
		elseif ($data->has('block')) {
			
		}
		elseif ($data->has('activate')) {
			$command->activate($data);
		}

		return $command->handle();

	}

	public function delete($data)
	{
		$users_id=$data->input('user_id');
		foreach ($users_id as $user_id) {
			$user=User::findOrFail($user_id);
			$this->Users[]=User::where('id',$user_id)->update(['active'=>'0']);
		}
	}

	public function activate($data)
	{
		$users_id=$data->input('user_id');
		foreach ($users_id as $user_id) {
			
			$this->Users[]=User::where('id',$user_id)->update(['active'=>'1']);
		}
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		return $this->Users;
	}

}
