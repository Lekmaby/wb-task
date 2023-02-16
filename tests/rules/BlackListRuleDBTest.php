<?php

namespace Src\Test\rules;

use PHPUnit\Framework\TestCase;
use Src\App\Database\ConnectionManager;
use Src\App\Database\DBConnection;
use Src\App\Database\MongoConnection;
use Src\App\Validator\Rules\BlackListRule;
use Src\App\Validator\Validator;

class BlackListRuleDBTest extends TestCase
{
	public function testBlackListDBRule(): void
	{

		$validator = new Validator('TestBlackListDBRule', __CLASS__);
		$db = $this->getConnection();
		$validator->addRule(new BlackListRule('name', $db));

		$this->assertTrue($validator->validate(['name' => 'supermike']));

		$validator->reset();
		$this->assertFalse($validator->validate(['name' => '1234']));

		$validator->reset();
		$this->assertFalse($validator->validate(['name' => '1111']));

		$validator->reset();
		$this->assertTrue($validator->validate(['name' => 'alal.some+domain@example.com']));
	}

	private function getConnection(): DBConnection
	{
		$cm = ConnectionManager::getInstance();
		$con = new MongoConnection();
		$con->connect('localhost', '2323');
		$cm->add($con, 'test_blacklists');

		return $cm->get('test_blacklists');
	}
}
