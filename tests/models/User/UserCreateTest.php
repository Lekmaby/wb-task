<?php

namespace Src\Test;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Src\App\Model\User;
use Src\App\Service\DatabaseConnection;
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

class UserCreateTest extends TestCase
{
	public function testUserCreate(): void
	{
		$name = 'konstantin17';
		$email = 'test@mail.ru';
		$date = Carbon::now();

		$user = new User();
		$user->name = $name;
		$user->email = $email;
		$user->created = $date;
		$valid = $user->create();

		$this->assertInstanceOf(User::class, $user);
		$this->assertNotFalse($valid);

		// check record in databese
		$db = $this->getConnection();
		$createdRow = $db->query('SELECT * FROM users WHERE id =' . $user->id);

		$this->assertEquals($name, $createdRow['name']);
		$this->assertEquals($email, $createdRow['email']);
		$this->assertEquals($date->format('Y-m-d H:i:s'), $createdRow['created']);

		//check that model was correctly validated
		$validator = new Validator('UserIsValid', User::class);
		$validator
			->addRule(new RequiredRule('name'))
			->addRule(new UserNameRule('name'))
			->addRule(new MinLengthRule('name', 8))
			->addRule(new BlackListRule('name'))
			->addRule(new UniqueRule('name', User::class))
			->addRule(new RequiredRule('email'))
			->addRule(new EmailRule('email'))
			->addRule(new DomainBlackListRule('email'))
			->addRule(new UniqueRule('email', User::class))
			->addRule(new RequiredRule('created'));

		$valid = $validator->validate($user);
		$this->assertTrue($valid);
	}

	private function getConnection(): DatabaseConnection{
		return DatabaseConnection::getInstance();
	}
}
