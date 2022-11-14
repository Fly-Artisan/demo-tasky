<?php namespace App\Models\taskdb\DS;
use FLY\Model\Algorithm\Model_Controllers;
use FLY_ENV\Util\Model\QueryBuilder;

class Task extends QueryBuilder {

/*        
	*******************************************************************************
	* can use transaction here                                                    *
	* example: use TRANSACTION;                                                   *
	* To use a transaction specify the namespace above this model class.          *
	* That is, copy and paste the namespace: use FLY\Model\Algorithm\TRANSACTION; * 
	* right above this model class.                                               *
	*******************************************************************************
*/

	protected $id;

	protected $name;

	protected $status;

	protected $dateAdded;

	use Model_Controllers;

	public function __construct($id="",$name="",$status="",$dateAdded="") 
	{
    	parent::__construct($this);
		$this->id        = $id;
		$this->name      = $name;
		$this->status    = $status;
		$this->dateAdded = $dateAdded;
    	$this->pk_names  = [ 'id' ];

    	$this->apply();
	}


	/**
	 * @return string[]
	 * @Todo It returns the model connection credentials
	 */
	protected function connection(): array
	{
    	return array(
			'host'

				=> 'default',

			'user'

				=> 'default',
				
			'password'

				=> 'default',

			'model'

				=> 'default'
		);
	}
}