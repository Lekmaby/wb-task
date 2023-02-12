<?php
declare(strict_types=1);

namespace Src\App\Service;

/**
 * Singleton class for work with bad/blacklisted usernames list
 */
class BadNamesList extends FileList
{
	private static ?BadNamesList $instance = null;

	public static function getInstance(): BadNamesList
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct()
	{
		$this->filename = 'src/resources/dicts/bad_names.txt';
		$this->readFile();
	}

	private function __clone()
	{
	}

	public function __wakeup()
	{
		throw new \RuntimeException("Cannot unserialize singleton");
	}
}
