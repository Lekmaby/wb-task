<?php

namespace Src\Test;

use PHPUnit\Framework\TestCase;
use Src\App\Model\User;
use Src\App\Model\UserValidationForTesters;
use Src\App\Validator\ValidationException;

class UserCreateForTestersTest extends TestCase
{
	public function testUserCreateForTesters(): void
	{
		$user = new User();
		$user->setValidatorClass(UserValidationForTesters::class);
		$user->name = 'Вася Тестер';
		$user->email = 'test@mail.r';
		$user->created = new \DateTime('now');
		$valid = $user->create();

		$this->assertInstanceOf(User::class, $user);
		$this->assertTrue($valid);
	}
}
