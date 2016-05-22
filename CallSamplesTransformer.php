<?php  namespace Cor\Transformers;

use Cor\Transformers\Transformer;

use App\Commands\CreateCellCommand;

use Carbon\Carbon;
class CallSamplesTransformer extends Transformer
{

	public function transform($sample)
	{
		$this->from = $sample;
		return[
			'network_coverage' 	=> $this->transformNetworkCoverageFrom('networkType'),  
			'network_type'  	=> $this->transformThis('networkType'),
			'network_state'		=> $this->transformThis('state'),
			'rx_level'			=> $this->transformRxLevel(),
			'rx_quality' 		=> $this->transformRxQuality(),
			'BER' 				=> $this->transformRxBER(),
			'call_state'		=> $this->transformThis('callState'),
			'cell' 				=> $this->transformCell(['cid','lac','operatorNumber']),   // it takes a command to process and return cell id
			'longtitude' 		=> $this->transformThis('longitude','0'),
			'latitude' 			=> $this->transformThis('latitude','0'),
			'altitude' 			=> $this->transformThis('altitude','0'),
			'speed'				=> $this->transformThis('speed','0'),
			'accuracy'			=> $this->transformThis('accuracy','0'),
			'battery_level'		=> $this->transformThis('batteryLevel'), 
			'wifi_status'		=> $this->transformThis('wifiStatus'),
			'ps_status'			=> $this->transformThis('mobileDataStatus'),
			'gps_status' 		=> $this->transformThis('gpsStatus'),
			'taken_at'			=> $this->transformTakenAtDate('time'),
			'phone_type' 		=> $this->transformThis('phoneType'),
			'cdmaDbm' 		    => $this->transformThis('cdmaDbm'),
			'isGsm' 			=> $this->transformThis('isGsm'),
			'psc' 				=> $this->transformThis('psc'),
			'evdoEcio' 			=> $this->transformThis(' evdoEcio'),
			'data_activity' 	=> $this->transformThis('dataActitity'),
			'evdoDbm' 			=> $this->transformThis('evdoDbm'),
			'evdiSnr' 			=> $this->transformThis('evdiSnr'),
			'cdmaEcio' 			=> $this->transformThis('cdmaEcio'),
			'operator_number'   => $this->transformThis('operatorNumber')
		];
	}

	/**
	 * [transformNetworkCoverageFrom description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function transformNetworkCoverageFrom($key = 'NetworkType')
	{
		$network_type = $this->transformThis($key);
		switch ($network_type) {
			case 'GPRS':
				$network_coverage = 'GSM'; 
			break;
			case 'LTE':
				$network_coverage = 'UMTS'; 
			break;	
			case 'EDGE':
				$network_coverage = 'UMTS'; 
			break;
			case 'HSDPA':
				$network_coverage = 'UMTS'; 
			break;
			case 'HSUPA':
				$network_coverage = 'UMTS'; 
			break;			
			default:
				$network_coverage = 'UMTS'; 
			break;
		}	
		return $network_coverage;
	}

	/**
	 * return a dbm unit of rx level
	 * @param  string $key [description]
	 * @return string      rx value in dbm
	 */
	public function transformRxLevel($key = 'gsmSignalStrength')
	{
		$rx_level = $this->transformThis($key);
		return (string) (((int) $rx_level * 2) - 113);
	}
	/**
	 * [transformRxQuality description]
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function transformRxQuality($key = 'rx_quality')
	{
		$rx_quality = $this->transformThis($key);
		/*
		* now it returns 0 cuz the andriod API doesnt get values
		 */
		return "0";
	}
	/**
	 * [transformRxBER description]
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function transformRxBER($key = "gsmBitErrorRate")
	{
		$rx_ber =  $this->transformThis($key);

		if((int) $rx_ber < 0)
			$rx_ber = "0";
		/*
		* now it returns 0 cuz the andriod API doesnt get values
		 */
		return $rx_ber;
	}
	/**
	 * transform miliseconds timestamp to date
	 * @param  string $key 
	 * @return string      taken at time for single sample
	 */
	public function transformTakenAtDate($key = 'taken_at')
	{
		$time = Carbon::createFromTimeStamp((float) $this->transformThis($key) / 1000);
		return $time->toDateTimeString();
	}
	/**
	 * get cell id from cell table
	 * @param  array  $keys [cell id , lac id and operator number]
	 * @return int          [cell db id]
	 */
	public function transformCell(array $keys)
	{
		$data["cell_id"] 		= $this->transformThis($keys[0]);
		$data["lac"] 	 		= $this->transformThis($keys[1]);
		$data["operator_code"]  = $this->transformThis($keys[2]);
		return CreateCellCommand::fromArray($data);	
	}
	/**
	 * over ride the transfrom collection method to decode json data
	 * @param  [string] $data [json string]
	 * @return [array]        [transformed array of call samples]
	 */
	public function transformCollectionFromJson($data)
	{
		$data = json_decode($data,'Array');
		return $this->transformCollection($data);
	}
}
//END


