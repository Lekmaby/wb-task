<?php

namespace Src\App\traits;

use Src\App\Database\DBConnection;

trait Archivable
{
	abstract public function getArchiveConnection(): DBConnection|null;

	public function create()
	{
		$result = parent::create();

		if ($result !== false) {
			$connection = $this->getArchiveConnection();
			$connection?->create(parent::$table, $this->toArray());
		}

		return $result;
	}

	public function update()
	{
		$result = parent::update();

		if ($result !== false) {
			$connection = $this->getArchiveConnection();
			$connection?->update(parent::$table, $this->id, $this->toArray());
		}

		return $result;
	}

	public function delete()
	{
		$result = parent::delete();

		if ($result !== false) {
			$connection = $this->getArchiveConnection();
			$connection?->delete(parent::$table, $this->id);
		}

		return $result;
	}
}
