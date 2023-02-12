<?php

namespace Src\Test\rules;

use PHPUnit\Framework\TestCase;
use Src\App\Validator\Rules\GreaterThanDateRule;
use Src\App\Validator\Validator;

class GreaterThanDateRuleTest extends TestCase
{
	public function testGreaterThanDateRule(): void
	{

		$validator = new Validator('TestGreaterThanDateRule', __CLASS__);
		$validator->addRule(new GreaterThanDateRule('deleted', 'created'));

		$this->assertTrue($validator->validate(['deleted' => '2023-01-01', 'created' => '2022-01-01']));

		$validator->reset();
		$this->assertFalse($validator->validate(['deleted' => '2021-01-01', 'created' => '2022-01-01']));

		$validator->reset();
		$this->assertFalse($validator->validate(['deleted' => '2022-01-14', 'created' => '2022-01-15']));

		$validator->reset();
		$this->assertTrue($validator->validate(['deleted' => '2022-01-01', 'created' => '2022-01-01']));
	}
}
