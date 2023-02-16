<?php

namespace Src\App\Database;

class ConnectionManager
{
	/**
	 *
	 *
	 * @var \Src\App\Database\DBConnection[]
	 */
	protected array $connections;

	private static ?ConnectionManager $instance = null;

	public static function getInstance(): ConnectionManager
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function add(DBConnection $connection, string $name = 'default'): bool
	{
		if (!array_key_exists($name, $this->connections)) {
			$this->connections[$name] = $connection;
			return true;
		}

		return false;
	}


	public function get(string $name): DBConnection|null
	{
		return $this->connections[$name] ?? null;
	}

	public function getDefault(): DBConnection|null
	{
		return $this->connections['default'] ?? null;
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
}
