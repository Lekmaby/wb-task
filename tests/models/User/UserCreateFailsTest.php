<?php

namespace Src\Test;

use PHPUnit\Framework\TestCase;
use Src\App\Model\User;
use Src\App\Validator\ValidationException;

class UserCreateFailsTest extends TestCase
{
	public function testUserCreateFails(): void
	{
		$this->expectException(ValidationException::class);

		$user = new User();
		$user->name = 'Вася Пупкин';
		$user->email = 'test@mail.r';
		$user->created = new \DateTime('now');
		$valid = $user->create();

		$this->assertInstanceOf(User::class, $user);
		$this->assertFalse($valid);
	}
}
