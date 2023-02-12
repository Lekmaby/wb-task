<?php

namespace Src\App\Validator\Rules;

use Src\App\Validator\Rule;

/**
 *    Check if email is valid
 */
class EmailRule extends Rule
{
	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (is_string($value)) {
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$this->valid = false;
				$this->message = "Invalid email format";
			}
		} else {
			$this->valid = false;
			$this->message = 'Field must be a string';
		}

		return $this->valid;
	}
}
