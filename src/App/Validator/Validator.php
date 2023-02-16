<?php

namespace Src\App\Validator;

use Src\App\Model\Record;

/**
 * Validator constructor class
 */
class Validator
{
	/**
	 * @var array Array of validation rules
	 */
	private array $rules;

	/**
	 * @var array Validation errors
	 */
	public array $errors;

	/**
	 * @param string $name Custom name for validator
	 * @param string|null $classname Class name in which validator should be applied
	 */
	public function __construct(
		public readonly string      $name,
		public readonly string|null $classname = null)
	{

	}

	/**
	 * Adds a rule to the validator
	 * @param Rule $rule
	 * @return $this
	 */
	public function addRule(Rule $rule): static
	{
		$this->rules[] = $rule;

		return $this;
	}

	/**
	 * Validate item with all rules
	 * @param array|Record $item  or object to validate
	 * @return bool Final validation result
	 */
	public function validate(array|Record $item): bool
	{
		$valid = true;
		if ($item instanceof Record) {
			$item = (array)$item;
		}

		/* @var Rule $item */
		foreach ($this->rules as $rule) {
			$s = $rule->validate($item);
			if (!$s) {
				$valid = false;
				$this->errors[] = $rule::class . ': Field "' . $rule->field . '": ' . $rule->getMessage();
			}
		}

		return $valid;
	}


	/**
	 * Reset validation result
	 * Call before new item will be validated
	 * @return void
	 */
	public function reset(): void
	{
		$this->errors = [];

		foreach ($this->rules as $rule) {
			$rule->reset();
		}
	}
}
