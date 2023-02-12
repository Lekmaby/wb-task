<?php

namespace Src\Test\rules;

use PHPUnit\Framework\TestCase;
use Src\App\Validator\Rules\RequiredRule;
use Src\App\Validator\Validator;

class RequiredRuleTest extends TestCase
{
	public function testRequiredRule(): void
	{

		$validator = new Validator('TestRequiredRule', __CLASS__);
		$validator->addRule(new RequiredRule('name'));

		$this->assertTrue($validator->validate(['name' => 'supermike']));

		$validator->reset();
		$this->assertFalse($validator->validate(['name' => '']));

		$validator->reset();
		$this->assertFalse($validator->validate(['name' => null]));

		$validator->reset();
		$this->assertTrue($validator->validate(['name' => 'alal.some+domain@example.com']));
	}
}
