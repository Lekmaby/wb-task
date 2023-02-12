<?php

namespace Src\App\Validator\Rules;

use Src\App\Validator\Rule;

/**
 *    Checks whether field value matches a given regular expression
 */
class RegExpRule extends Rule
{
	public function __construct($field, public string $pattern)
	{
		parent::__construct($field);
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (is_string($value)) {
			if (!preg_match($this->pattern, $value)) {
				$this->valid = false;
				$this->message = "Invalid pattern";
			}
		} else {
			$this->valid = false;
			$this->message = 'Field must be a string';
		}

		return $this->valid;
	}
}
