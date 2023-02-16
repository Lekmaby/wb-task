<?php

namespace Src\App\Database;

use Src\App\Util\Response;

class MongoConnection implements DBConnection
{
	protected $client;

	public function connect($host, $port)
	{
		try {
			$this->client = new MongoDB\Client("mongodb://'.$host.':'.$port.'");
		} catch (\Exception $e) {
			Response::sendException($e);
		}
	}

	public function getConnection()
	{
		return $this->client;
	}

	public function create(string $source, array $data): array
	{
		$collection = $this->getConnection()[$source];
		$result = $collection->insertOne( $data );

		return $this->get($source, $result->getInsertedId());
	}

	public function update(string $source, int|string $id, array $data): array|false
	{
		$collection = $this->getConnection()[$source];

		$updateResult = $collection->updateOne(
			[ '_id' => $id ],
			[ '$set' => $data]
		);

		return $updateResult->getMatchedCount() > 0 ? $this->get($source, $id) : false;
	}

	public function delete(string $source, int|string $id): bool
	{
		$collection = $this->getConnection()[$source];

		$deleteResult = $collection->deleteOne(['_id' => $id]);

		return $deleteResult->getDeletedCount() > 0;
	}

	public function get(string $source, int|string $id): array
	{
		return $this->getConnection()[$source]->findOne(['_id' => $id]);
	}

	public function find(string $source, string $key, int|string $value): array
	{
		return $this->getConnection()[$source]->findOne([$key => $value]);
	}
}
