<?php

namespace Src\Test\rules;

use PHPUnit\Framework\TestCase;
use Src\App\Validator\Rules\EmailRule;
use Src\App\Validator\Validator;

class EmailRuleTest extends TestCase
{
	public function testEmailRule(): void
	{

		$validator = new Validator('TestEmailRule', __CLASS__);
		$validator->addRule(new EmailRule('email'));

		$this->assertTrue($validator->validate(['email' => 'test@example.com']));

		$validator->reset();
		$this->assertFalse($validator->validate(['email' => 'test@']));

		$validator->reset();
		$this->assertFalse($validator->validate(['email' => 'test']));

		$validator->reset();
		$this->assertTrue($validator->validate(['email' => 'alal.some+domain@example.com']));
	}
}
