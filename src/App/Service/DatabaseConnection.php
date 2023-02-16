<?php
declare(strict_types=1);

namespace Src\App\Service;

use PDO;
use Src\App\Util\Response;

class DatabaseConnection
{
	private static ?DatabaseConnection $instance = null;
	public \PDO|null $dbh = null;

	public static function getInstance(): DatabaseConnection
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct()
	{
	}

	private function __clone()
	{
	}

	public function __wakeup()
	{
		throw new \RuntimeException("Cannot unserialize singleton");
	}

	/**
	 * Run SQL query
	 * @param string $sql SQL query
	 * @return bool SQL query result
	 */
	public function query(string $sql): array
	{
		try {
			$result = $this->dbh->query($sql)->fetch(PDO::FETCH_ASSOC);
			echo date('Y-m-d H:i:s') . ' query ' . $sql;
		} catch (\PDOException $e) {
			Response::sendException($e);
		}

		return $result;
	}
}
