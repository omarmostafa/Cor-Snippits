<?php namespace Cor\Transformers;

use Cor\Transformers\Transformer;
use App\Asset;
use App\CallSample;
use App\Call;
use App\Cell;
use Carbon\Carbon;
use App\Operator;

class CallTransformer extends Transformer{
	
	/**
	 * [transform description]
	 * @param  Array $callData  [description]
	 * @return Array Call       [description]
	 */
	public function transform($sampleData)
	{

		$this->from             = json_decode($sampleData["call_info"], 'Array');
		$this->from["sampleId"] = $sampleData["id"];
		$this->from['assetId'] =$sampleData['asset_id'];
		$this->from['userId'] =$this->getUserID($sampleData['asset_id']);
		$this->from['operatorId']=$this->getOperatorId($this->transformThis('operatorNumber'));
		return [
			"call_type" 		 => $this->transformThis("callType","voice"),
			"call_log" 			 => $this->transformThis("callLog"),
			"destination_number" => $this->transformThis("destinationNumber"),
			"call_period" 		 => $this->transformPeriodFrom("startTime", "endTime"),
			"start_time"		 => $this->transformTimestamp($this->transformThis('startTime')),
			"end_time"			 => $this->transformTimestamp($this->transformThis('endTime')),
			"call_rate" 		 => $this->transformThis("callRate",'5'),
			"sample_id" 		 => $this->transformThis("sampleId"),
			"asset_id" 		     => $this->transformThis("assetId"),
			"user_id" 		     => $this->transformThis("userId"),
			"operator_id" 		 => $this->transformThis("operatorId"),
		];
	}

	/**
	 * Takes two micro second unix time stamp and produce difference in seconds
	 * @param  string $startTime start time in micro seconds unix timestamp
	 * @param  string $endTime   end time in micro seconds unix timestamp
	 * @return string            difference in seconds
	 */
	private function transformPeriodFrom($startTime , $endTime)
	{	
		// converting from milliseconds
		$startTime  = $this->transformTimestamp($this->from[$startTime]);
		$endTime  = $this->transformTimestamp($this->from[$endTime]);
		// should be converted to seconds if we need to
		// or implement single interface to deal with the supported formats
		
		// return call period in seconds
		 return (string) $call_period = $startTime->diffInSeconds($endTime); 
	}
	/**
	 * get user_id for this call
	 * @return [type] [description]
	 */
	public function getUserID($asset_id)
	{
		return $Asset=Asset::find($asset_id)->user_id;
	}
	
}
//END