<?php
declare(strict_types=1);

namespace Src\App\Model;

use Exception;
use Src\App\Database\ConnectionManager;
use Src\App\Database\DBConnection;
use Src\App\traits\Archivable;

class User extends Record
{
	use Archivable;

	public static string $table = 'users';

	public int $id;
	public string $name;
	public string $email;
	public \DateTime $created;
	public \DateTime|null $deleted = null;
	public string|null $notes = null;
	protected string $validatorClass = UserValidation::class;

	/**
	 * Validate user
	 * @throws Exception
	 */
	public function isValid($action = 'default'): bool
	{
		return (new ${$this->validatorClass}($this, $action))->isValid();
	}

	public function delete(): bool
	{
		$this->deleted = new \DateTime('now');
		return parent::delete();
	}

	public function getFields(): array
	{
		return ['id', 'name', 'email', 'created', 'deleted', 'notes'];
	}

	public function toArray(): array
	{
		$result = [];
		foreach ($this->getFields() as $field) {
			$result[$field] = $this->$field;
		}

		return $result;
	}

	public function fill(array $data): User
	{
		foreach ($this->getFields() as $field) {
			$this->$field = $data[$field] ?? $this->$field;
		}

		return $this;
	}

	public function getArchiveConnection(): DBConnection|null
	{
		return ConnectionManager::getInstance()->get('archive');
	}

	protected function afterCreate()
	{
		$mongo = ConnectionManager::getInstance()->get('mongo');
		$mongo->create($this::$table, $this->toArray());
	}
}
