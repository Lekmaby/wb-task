<?php

namespace Src\Test;

use PHPUnit\Framework\TestCase;

class DictBadNamesTest extends TestCase
{
	public function testFile(): void
	{
		$filename = 'src/resources/dicts/bad_names.txt';
		$this->assertFileExists($filename);

		$words = file($filename, FILE_IGNORE_NEW_LINES);
		$this->assertGreaterThan(2, count($words));
	}
}
