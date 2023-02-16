<?php

namespace Src\App\Validator\Rules;

use Src\App\Database\DBConnection;
use Src\App\Service\BadDomainsList;
use Src\App\Validator\Rule;

/**
 * Checks if email domain is blacklisted
 */
class DomainBlackListRule extends Rule
{
	private array $blackList;
	private ?DBConnection $connection;

	public function __construct($field, DBConnection $connection = null)
	{
		parent::__construct($field);
		if ($connection) {
			$this->connection = $connection;
		} else {
			$badDomainsInstance = BadDomainsList::getInstance();
			$this->blackList = $badDomainsInstance->items;
		}
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (is_string($value)) {
			$domain = substr($value, strpos($value, '@') + 1);
			if ($this->connection) {
				$result = $this->connection->find('domains', 'name', $value);

				if ($result !== false) {
					$this->valid = false;
					$this->message = "Domain '$domain' is blacklisted";
				}

			} elseif (in_array($domain, $this->blackList, true)) {
				$this->valid = false;
				$this->message = "Domain '$domain' is blacklisted";
			}
		} else {
			$this->valid = false;
			$this->message = 'Field must be a string';
		}

		return $this->valid;
	}
}
