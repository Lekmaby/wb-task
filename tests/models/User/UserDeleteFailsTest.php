<?php

namespace Src\Test;

use PHPUnit\Framework\TestCase;
use Src\App\Model\User;
use Src\App\Validator\ValidationException;

class UserDeleteFailsTest extends TestCase
{
	public function testUserDeleteFails(): void
	{
		$this->expectException(ValidationException::class);

		$user = new User();
		$user->name = 'konstantin17';
		$user->email = 'test@mail.ru';
		$user->created = new \DateTime('+1 day');
		$valid = $user->delete();

		$this->assertInstanceOf(User::class, $user);
		$this->assertFalse($valid);
	}
}
