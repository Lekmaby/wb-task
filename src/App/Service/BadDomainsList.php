<?php
declare(strict_types=1);

namespace Src\App\Service;

/**
 * Singleton class for work with bad/blacklisted email domains list
 */
class BadDomainsList extends FileList
{
	private static ?BadDomainsList $instance = null;

	public static function getInstance(): BadDomainsList
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct()
	{
		$this->filename = 'src/resources/dicts/bad_domains.txt';
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
