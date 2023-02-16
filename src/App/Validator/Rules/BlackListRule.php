<?php

namespace Src\App\Validator\Rules;

use Src\App\Database\DBConnection;
use Src\App\Service\BadNamesList;
use Src\App\Validator\Rule;

/**
 * Checks if field value is blacklisted
 */
class BlackListRule extends Rule
{
	private array $blackList;

	private ?DBConnection $connection;

	public function __construct($field, DBConnection $connection = null)
	{
		parent::__construct($field);
		if ($connection) {
			$this->connection = $connection;
		} else {
			$badNamesInstance = BadNamesList::getInstance();
			$this->blackList = $badNamesInstance->items;
		}
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (is_string($value)) {
			if ($this->connection) {
				$result = $this->connection->find('names', 'name', $value);

				if ($result !== false) {
					$this->valid = false;
					$this->message = 'Field is blacklisted';
				}

			} elseif (in_array($value, $this->blackList, true)) {
				$this->valid = false;
				$this->message = 'Field is blacklisted';
			}
		} else {
			$this->valid = false;
			$this->message = 'Field must be a string';
		}

		return $this->valid;
	}
}
