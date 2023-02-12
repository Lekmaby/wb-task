<?php

namespace Src\Test;

use PHPUnit\Framework\TestCase;
use Src\App\Service\Logger;

class LoggerTest extends TestCase
{
	public function testLogger(): void
	{
		$firstCall = Logger::getInstance();
		$secondCall = Logger::getInstance();

		$this->assertInstanceOf(Logger::class, $firstCall);
		$this->assertSame($firstCall, $secondCall);
	}
}
