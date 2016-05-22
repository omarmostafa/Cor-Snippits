<?php namespace App\Commands;

use Exception;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use App\Complain;

use Cor\Transformers\ComplainTransformer;
 
class CreateComplainCommand extends Command implements SelfHandling {

	/**
	 * [$complain description]
	 * @var [type]
	 */
	protected $complain;
	/**
	 * [$transformer description]
	 * @var [type]
	 */
	protected $transformer;
	/**
	 * Named constructor style
	 * NOTE : dependencay inject the transformer from the IOC container
	 * @return void
	 */
	private function __construct()
	{
		$this->transformer =  new ComplainTransformer;
	}


	public static function fromJson(array $data)
	{
		$command = new CreateComplainCommand;
		$command->complain = $command->transformer->transform($data);
		return $command->handle();
	}
	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{	
		Complain::create([
			"user_id" 			=> $this->complain["user_id"],
			"asset_id"			=> $this->complain["asset_id"],
			"problem_type"		=> $this->complain["problem_type"],
			"problem_sub_type"  => $this->complain["problem_sub_type"],
			"problem_details"	=> $this->complain["problem_details"],
			"sample"			=> $this->complain['sample'],
			"time"				=> $this->complain['time'],
			"lat"				=> $this->complain['lat'],
			"long"				=> $this->complain['long'],
			"operator_id"       => $this->complain['operator_id']
			]);
		
		return true;
	}

}
