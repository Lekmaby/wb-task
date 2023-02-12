<?php

namespace Src\App\Validator\Rules;

use Src\App\Validator\Rule;

/**
 *    Checks if the field is not empty
 */
class RequiredRule extends Rule
{
	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (empty($value)) {
			$this->valid = false;
			$this->message = "Field is required";
		}

		return $this->valid;
	}
}
