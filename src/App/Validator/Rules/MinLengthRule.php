<?php

namespace Src\App\Validator\Rules;

use Src\App\Validator\Rule;

/**
 *    Checks if given field value length is greater than given min length.
 */
class MinLengthRule extends Rule
{
	/**
	 * @param string $field name of the field to validate
	 * @param int $minLength Min length of field value
	 */
	public function __construct(string $field, public int $minLength = 0)
	{
		parent::__construct($field);
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (is_string($value)) {
			if (strlen($value) < $this->minLength) {
				$this->valid = false;
				$this->message = 'Field must be at least ' . $this->minLength;
			}
		} else {
			$this->valid = false;
			$this->message = 'Field must be a string';
		}

		return $this->valid;
	}
}
