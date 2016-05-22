<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use App\Cell;

use App\Operator;

class CreateCellCommand extends Command implements SelfHandling {

	/**
	 * App\Operator
	 * @var Object
	 */
	protected $operator;
	/**
	 * App\Cell
	 * @var Object
	 */
	protected $cell;

	/**
	 * named constructor style
	 *
	 * @return void
	 */
	private function __construct()
	{
		$this->operator = new Operator;
		$this->cell     = new Cell;
	}

	/**
	 * contain cell data from array
	 * @param  array  $data cell essential data
	 */
	public static function fromArray(array $data)
	{
		$command = new CreateCellCommand;
		$command->cell_id 		= $data["cell_id"];
		$command->lac 	  		= $data["lac"];
		$command->operator_id	= $command->operator->whereCode($data["operator_code"])->first()->id;
		return $command->handle();
	} 

	/**
	 * create or get the cell id
	 *
	 * @return int cell id
	 */
	public function handle()
	{
		$cell = $this->cell->firstOrCreate([
				"cell_id" 		=> $this->cell_id,
				"lac"	  		=> $this->lac,
				"operator_id"	=> $this->operator_id 
			]);
		 return $cell->id;
	}

}
