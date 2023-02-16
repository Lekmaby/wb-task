<?php

namespace Src\App\Database;

use PDO;
use Src\App\Util\Response;

class MySqlConnection implements DBConnection
{
	protected \PDO $pdo;

	public function connect($host, $port, $user = null, $password = null, $database = null)
	{
		try {
			$dsn = 'mysql:dbname=' . $database . ';host=' . $host . ':' . $port;
			$this->pdo = new PDO($dsn, $user, $password);
		} catch (\PDOException $e) {
			Response::sendException($e);
		}
	}

	public function getConnection()
	{
		return $this->pdo;
	}

	public function create(string $source, array $data): array|false
	{
		try {
			$sql = "INSERT INTO " . $source;
			$sql .= " (" . implode(', ', array_keys($data)) . ")";
			$sql .= " VALUES(:" . implode(', :', array_values($data)) . ")";

			$query = $this->getConnection()->prepare($sql);

			$result = $query->execute($data);
			$last_id = $this->getConnection()->lastInsertId();
		} catch (\PDOException $e) {
			Response::sendException($e);
		}

		return $result ? $this->get($source, $last_id) : false;

	}

	public function update($source, int|string $id, array $data): array|false
	{
		try {
			$setData = [];
			foreach ($data as $key => $val) {
				$setData[] = $key . "='" . $val . "'";
			}
			$sql = "UPDATE " . $source;
			$sql .= " SET " . implode(', ', $setData) . ")";
			$sql .= " WHERE id " . $id;

			$query = $this->getConnection()->prepare($sql);

			$result = $query->execute();

		} catch (\PDOException $e) {
			Response::sendException($e);
		}

		return $result ? $this->get($source, $id) : false;
	}

	public function delete($source, int|string $id): bool
	{
		try {
			$sql = "DELETE FROM " . $source;
			$sql .= " WHERE id " . $id;

			$query = $this->getConnection()->prepare($sql);
			$result = $query->execute();
		} catch (\PDOException $e) {
			Response::sendException($e);
		}

		return $result;
	}

	public function get($source, int|string $id): array
	{
		try {
			$sql = 'SELECT * FROM ' . $source . ' WHERE id = ' . $id;
			$stmt = $this->getConnection()->query($sql);
			$result = $stmt->fetch(PDO::FETCH_LAZY);
		} catch (\PDOException $e) {
			Response::sendException($e);
		}

		return $result;
	}

	public function find($source, string $key, int|string $value): array|false
	{
		try {
			$sql = 'SELECT * FROM ' . $source . ' WHERE ' . $key . ' = ' . $value;
			$stmt = $this->getConnection()->query($sql);
			$result = $stmt->fetch(PDO::FETCH_LAZY);
		} catch (\PDOException $e) {
			Response::sendException($e);
		}

		return $result['id'] ? $result : false;
	}
}
