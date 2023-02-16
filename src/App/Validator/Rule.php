<?php

namespace Src\App\Validator;

use Src\App\Model\Record;
use Src\App\Service\DatabaseConnection;

/**
 * Validation base rule
 */
abstract class Rule
{
	/**
	 * @var string|null Name of the field in item object that rule will check
	 */
	public readonly string|null $field;
	/**
	 * @var bool|null Validation rule result
	 */
	protected bool|null $valid;
	/**
	 * @var string|null Validation rule result description
	 */
	protected string|null $message;
	protected readonly DatabaseConnection $db;

	public function __construct(string $field)
	{
		$this->field = $field;
		$this->db = DatabaseConnection::getInstance();
	}

	/**
	 * Validate item with rule
	 * @param $item
	 * @return bool
	 */
	abstract public function validate(array|Record $item): bool;

	/**
	 * Reset rule validation result
	 * @return void
	 */
	public function reset()
	{
		$this->valid = null;
		$this->message = null;
	}

	/**
	 * Getter for validation result
	 * @return bool
	 */
	public function isValid(): bool
	{
		return $this->valid;
	}

	/**
	 * Getter for validation result message
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}
}
