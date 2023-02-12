<?php

namespace Src\Test\rules;

use PHPUnit\Framework\TestCase;
use Src\App\Validator\Rules\BlackListRule;
use Src\App\Validator\Validator;

class BlackListRuleTest extends TestCase
{
	public function testBlackListRule(): void
	{

		$validator = new Validator('TestBlackListRule', __CLASS__);
		$validator->addRule(new BlackListRule('name'));

		$this->assertTrue($validator->validate(['name' => 'supermike']));

		$validator->reset();
		$this->assertFalse($validator->validate(['name' => '1234']));

		$validator->reset();
		$this->assertFalse($validator->validate(['name' => '1111']));

		$validator->reset();
		$this->assertTrue($validator->validate(['name' => 'alal.some+domain@example.com']));
	}
}
