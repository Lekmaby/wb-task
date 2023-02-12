<?php

namespace Src\App\Validator\Rules;

use Src\App\Validator\Rule;

/**
 *    Check if there is no record in the database with the same field value
 */
class UniqueRule extends Rule
{
	public function __construct($field, private readonly string $class)
	{
		parent::__construct($field);
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];
		$table = $this->class::$table;
		$sql = 'SELECT COUNT(1) FROM ' . $table . ' WHERE ' . $this->field . ' = "' . $value . '" LIMIT 1';
		$result = $this->db->dbh !== null ? $this->db->query($sql) : 0;

		if ($result > 0) {
			$this->valid = false;
			$this->message = 'Field must be unique';
		}

		return $this->valid;
	}
}
