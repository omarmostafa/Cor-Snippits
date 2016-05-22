<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use App\Call;

use App\Sample;

use App\CallSample;

use Cor\Transformers\CallTransformer;

use Cor\Transformers\CallSamplesTransformer;

class ExtractCallCommand extends Command implements SelfHandling {
	/**
	 * Cor\Transformers\CallTransformer
	 * @var Object
	 */
	protected $callTransformer;
	
	/**
	 * Cor\Transformers\CallSampleTransformer
	 * @var Object
	 */
	protected $callSamplesTransformer ;
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->callTransformer 		  = new CallTransformer;
		$this->callSamplesTransformer = new CallSamplesTransformer;
	}

	/**
	 * [fromObject description]
	 * @param  Sample $data [description]
	 * @return [type]       [description]
	 */
	public function fromObject(Sample $data)
	{
		return $this->fromArray($data->toArray());
	}

	/**
	 * [fromArray description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function fromArray($data)
	{
		$this->callData    = $this->callTransformer->transform($data);
		$this->callSamples = $this->callSamplesTransformer->transformCollectionFromJson($data["sample"]); 
		return $this->handle();
	}

	/**
	 * decide if data is object or json array
	 * @param  [mixed] $data [call data]
	 */
	public function from($data)
	{
		if(is_object($data))
			return $this->fromObject($data);
		elseif(is_array($data))
			return $this->fromArray($data);
	}

	/**
	 * create new call and insert related call samples to it
	 *
	 * @return  object Call object
	 */
	public function handle()
	{
		// creating new CallSample objects
		foreach ($this->callSamples as $callSample) 
		{
			// callsamples object array
			$callSamples[] = new CallSample($callSample);
		}

		// creating a call instance
		$call = new Call($this->callData);
		$call->save();  // saving the call object
		$call->callSamples()->saveMany($callSamples);
		return $call;
	}

}
