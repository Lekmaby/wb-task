<?php
declare(strict_types=1);

namespace Src\App\Service;

class Logger
{
	private static ?Logger $instance = null;

	public static function getInstance(): Logger
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

	public function addLog($message): bool
	{
		echo date('Y-m-d H:i:s') . ' ' . $message;

		return true;
	}
}
