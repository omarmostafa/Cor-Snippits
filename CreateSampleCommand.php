<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use Cor\Transformers\SampleTransformer;

use App\Sample;
class CreateSampleCommand extends Command implements SelfHandling {

	/**
	 * Cor\Transformers\SampleTransformer
	 * @var Object
	 */
	protected $transformer;

	/**
	 * App\Sample
	 * @var Object
	 */
	protected $sample;
	
	/**
	 * named constructor
	 *
	 * @return void
	 */
	private function __construct()
	{
		$this->transformer = new SampleTransformer;	
		$this->sample = new Sample;
	}

	public static function fromJson(array $data)
	{
		$command = new CreateSampleCommand;
		$command->sampleData = $command->transformer->transform($data);
		return $command->handle();
	}
	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		try{

		$this->sample->createNewSample($this->sampleData);
		}
		catch(\Exception $e)
		{
			var_dump($e->getMessage());
		}
	}

}
