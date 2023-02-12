<?php

namespace Src\App\Validator\Rules;

use Src\App\Service\BadNamesList;
use Src\App\Validator\Rule;

/**
 * Checks if field value is blacklisted
 */
class BlackListRule extends Rule
{
	private array $blackList;

	public function __construct($field)
	{
		parent::__construct($field);
		$badNamesInstance = BadNamesList::getInstance();
		$this->blackList = $badNamesInstance->items;
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (is_string($value)) {
			if (in_array($value, $this->blackList, true)) {
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
