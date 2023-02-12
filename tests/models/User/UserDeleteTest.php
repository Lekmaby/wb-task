<?php

namespace Src\Test;

use PHPUnit\Framework\TestCase;
use Src\App\Model\User;

class UserDeleteTest extends TestCase
{
	public function testUserDelete(): void
	{
		$user = new User();
		$user->name = 'konstantin17';
		$user->email = 'test@mail.ru';
		$user->created = new \DateTime('-1 day');
		$valid = $user->delete();

		$this->assertInstanceOf(User::class, $user);
		$this->assertTrue($valid);

		$user->created = new \DateTime('now');
		$valid = $user->delete();

		$this->assertTrue($valid);
	}
}
