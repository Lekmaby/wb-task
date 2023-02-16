<?php

namespace Src\App\Validator\Rules;

use Src\App\Model\Record;
use Src\App\Validator\Rule;

/**
 *    Check if there is no record in the Database with the same field value
 */
class UniqueRule extends Rule
{
	public function __construct($field, private readonly string $class)
	{
		parent::__construct($field);
	}

	public function validate(Record|array $item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		$connection = is_array($value) ? (new $this->class)->getConnection() : $item->getConnection();


		$table = is_array($value) ? (new $this->class)::$table : $item::$table;
		$existItem = $connection->find($table, $this->field, $value);

		if ($existItem !== false) {
			$this->valid = false;
			$this->message = 'Field must be unique';
		}

		return $this->valid;
	}
}
