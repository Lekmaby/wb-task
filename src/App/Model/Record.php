<?php

namespace Src\App\Model;

use Src\App\Service\DatabaseConnection;
use Src\App\Service\Logger;

abstract class Record
{
	protected readonly Logger $logger;
	protected readonly DatabaseConnection $db;
	public static string $table;

	public function __construct(Logger $logger, DatabaseConnection $db)
	{
		$this->logger = $logger;
		$this->db = $db;
	}

	/**
	 * Get record by id
	 * @param int $id
	 * @return array
	 */
	public function get(int $id): array
	{
		$result = $this->db->query('get record');
		return [];
	}

	/**
	 * Creates new record
	 * @return bool
	 */
	public function create()
	{
		if ($this->isValid('create')) {
			$result = $this->db->query('create record');
			$this->logger->addLog('created user');
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
			$result = $this->db->query('update record');
			$this->logger->addLog('updated user');
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
			$result = $this->db->query('delete record');
			$this->logger->addLog('deleted user');
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
}
