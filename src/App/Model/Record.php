<?php

namespace Src\App\Model;

use Src\App\Database\ConnectionManager;
use Src\App\Database\DBConnection;
use Src\App\Service\Logger;

abstract class Record
{
	protected readonly Logger $logger;
	protected readonly DBConnection $db;
	public static string $table;
	protected string $validatorClass;
	protected int $id;

	public function __construct(Logger $logger, DBConnection $db = null)
	{
		$this->logger = $logger;
		$this->db = $db ?? ConnectionManager::getInstance()->getDefault();
	}

	public function getConnection(): DBConnection{
		return $this->db;
	}

	/**
	 * Get record by id
	 * @param int $id
	 * @return array
	 */
	public function get(int $id): array
	{
		return $this->db->get($this::$table, $id);
	}

	/**
	 * Creates new record
	 * @return bool
	 */
	public function create()
	{
		if ($this->isValid('create')) {
			$result = $this->db->create($this::$table, $this->toArray());

			if(isset($result['id'])){
				$this->logger->addLog('created user');
				$this->fill($result);
				$this->afterCreate();
			} else {
				$this->logger->addLog('error when creating user');
			}
			return $result;
		}

		$this->logger->addLog('error on create user');
		return false;
	}

	/**
	 * Updates record
	 * @return bool
	 */
	public function update()
	{
		if ($this->isValid('update')) {
			$result = $this->db->update($this::$table, $this->id, $this->toArray());
			$this->logger->addLog('updated user');
			$this->fill($result);
			$this->afterUpdate();
			return $result;
		}

		$this->logger->addLog('error on update user');
		return false;
	}

	/**
	 * Delete record by id
	 * @return bool
	 */
	public function delete()
	{
		if ($this->isValid('delete')) {
			$result = $this->db->delete($this::$table, $this->id);
			$this->logger->addLog('deleted user');
			$this->afterDelete();
			return $result;
		}

		$this->logger->addLog('error on delete user');
		return false;
	}

	/**
	 * Validate record
	 * @param string $action Action for which the record needs to be confirmed
	 * @return bool
	 */
	public function isValid(string $action = 'default'): bool
	{
		return true;
	}

	public function setValidatorClass($classname)
	{
		$this->validatorClass = $classname;
	}

	abstract public function toArray(): array;

	abstract public function fill(array $data): Record;

	abstract public function getFields(): array;

	protected function afterCreate()
	{

	}

	protected function afterUpdate()
	{

	}

	protected function afterDelete()
	{

	}
}
