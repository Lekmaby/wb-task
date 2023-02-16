<?php
declare(strict_types=1);

namespace Src\App\Model;

use Src\App\Database\ConnectionManager;
use Src\App\Service\DatabaseConnection;
use Src\App\Validator\ModelValidation;
use Src\App\Validator\Rules\BlackListRule;
use Src\App\Validator\Rules\Custom\UserNameRule;
use Src\App\Validator\Rules\DomainBlackListRule;
use Src\App\Validator\Rules\EmailRule;
use Src\App\Validator\Rules\MinLengthRule;
use Src\App\Validator\Rules\RequiredRule;
use Src\App\Validator\Rules\UniqueRule;

class UserValidationForTesters extends ModelValidation
{
	public function addRules()
	{
		$this->validator
			->addRule(new RequiredRule('name'))
			->addRule(new UserNameRule('name'))
			->addRule(new MinLengthRule('name', 8))
			->addRule(new UniqueRule('name', User::class))
			->addRule(new RequiredRule('email'))
			->addRule(new EmailRule('email'))
			->addRule(new UniqueRule('email', User::class))
			->addRule(new RequiredRule('created'));

		$this->addBlackliListRules();
	}

	public function addBlackliListRules()
	{
		$db = ConnectionManager::getInstance()->get('blacklists');
		$this->validator
			->addRule(new BlackListRule('name', $db));
	}
}
