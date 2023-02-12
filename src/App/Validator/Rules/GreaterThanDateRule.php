<?php

namespace Src\App\Validator\Rules;

use Carbon\Carbon;
use Src\App\Validator\Rule;

/**
 *    Checks if the date is greater than other date
 */
class GreaterThanDateRule extends Rule
{
	/**
	 * @param $field - field with first date
	 * @param $fieldForCompare - second date for comparison
	 */
	public function __construct($field, protected $fieldForCompare)
	{
		parent::__construct($field);
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = Carbon::parse($item[$this->field]);
		$valueForCompare = Carbon::parse($item[$this->fieldForCompare]);

		if ($value->isValid() && $valueForCompare->isValid()) {
			if ($value->lessThan($valueForCompare)) {
				$this->valid = false;
				$this->message = 'Field must be less than' . $valueForCompare->toString();
			}
		} else {
			$this->valid = false;
			$this->message = 'Fields must be a valid date';
		}

		return $this->valid;
	}
}
