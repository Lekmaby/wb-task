<?php

namespace Src\App\Validator\Rules;

use Src\App\Service\BadDomainsList;
use Src\App\Validator\Rule;

/**
 * Checks if email domain is blacklisted
 */
class DomainBlackListRule extends Rule
{
	private array $blackList;

	public function __construct($field)
	{
		parent::__construct($field);
		$badDomainsInstance = BadDomainsList::getInstance();
		$this->blackList = $badDomainsInstance->items;
	}

	public function validate($item): bool
	{
		$this->valid = true;
		$value = $item[$this->field];

		if (is_string($value)) {
			$domain = substr($value, strpos($value, '@') + 1);
			if (in_array($domain, $this->blackList, true)) {
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
