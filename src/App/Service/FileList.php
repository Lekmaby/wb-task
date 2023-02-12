<?php
declare(strict_types=1);

namespace Src\App\Service;

class FileList
{
	public readonly array $items;
	protected string $filename;

	private function __construct($filename)
	{
		$this->filename = $filename;
		$this->readFile();
	}

	protected function readFile()
	{
		if (file_exists($this->filename)) {
			$this->items = file($this->filename, FILE_IGNORE_NEW_LINES);
		}
	}
}
