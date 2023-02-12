<?php
declare(strict_types=1);

namespace Src\App\Model;

use Exception;
use Src\App\Validator\Rules\BlackListRule;
use Src\App\Validator\Rules\Custom\UserNameRule;
use Src\App\Validator\Rules\DomainBlackListRule;
use Src\App\Validator\Rules\EmailRule;
use Src\App\Validator\Rules\GreaterThanDateRule;
use Src\App\Validator\Rules\MinLengthRule;
use Src\App\Validator\Rules\RequiredRule;
use Src\App\Validator\Rules\UniqueRule;
use Src\App\Validator\ValidationException;
use Src\App\Validator\Validator;

class User extends Record
{
	public static string $table = 'users';

	public int $id;
	public string $name;
	public string $email;
	public \DateTime $created;
	public \DateTime|null $deleted = null;
	public string|null $notes = null;

	/**
	 * Validate user
	 * @throws Exception
	 */
	public function isValid($action = 'default'): bool
	{
		$validator = new Validator('UserIsValid', __CLASS__);
		$validator
			->addRule(new RequiredRule('name'))
			->addRule(new UserNameRule('name'))
			->addRule(new MinLengthRule('name', 8))
			->addRule(new BlackListRule('name'))
			->addRule(new UniqueRule('name', __CLASS__))
			->addRule(new RequiredRule('email'))
			->addRule(new EmailRule('email'))
			->addRule(new DomainBlackListRule('email'))
			->addRule(new UniqueRule('email', __CLASS__))
			->addRule(new RequiredRule('created'));

		if ($action === 'delete') {
			$validator->addRule(new GreaterThanDateRule('deleted', 'created'));
		}

		$valid = $validator->validate($this);

		if (!$valid) {
			throw new ValidationException(implode('; ' . PHP_EOL, $validator->errors));
		}

		return $valid;
	}

	public function delete(): bool
	{
		$this->deleted = new \DateTime('now');
		return parent::delete();
	}

}
