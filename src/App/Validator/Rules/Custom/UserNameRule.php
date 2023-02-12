<?php

namespace Src\App\Validator\Rules\Custom;

use Src\App\Validator\Rules\RegExpRule;

/**
 *    Checks whether username matches predefined regular expression
 */
class UserNameRule extends RegExpRule
{
	public string $pattern = "/^[a-z0-9]+$/";

	public function __construct($field)
	{
		parent::__construct($field, $this->pattern);
	}
}
