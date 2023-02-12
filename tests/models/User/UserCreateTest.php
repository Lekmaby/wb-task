<?php

namespace Src\Test;

use PHPUnit\Framework\TestCase;
use Src\App\Model\User;

class UserCreateTest extends TestCase
{
	public function testUserCreate(): void
	{
		$user = new User();
		$user->name = 'konstantin17';
		$user->email = 'test@mail.ru';
		$user->created = new \DateTime('now');
		$valid = $user->create();

		$this->assertInstanceOf(User::class, $user);
		$this->assertTrue($valid);
	}
}
