<?php

namespace Src\App\Database;

interface DBConnection
{
	public function connect($host, $port, $user=null, $password=null, $database=null);
	public function getConnection();
	public function get(string $source, int|string $id): array;
	public function find(string $source, string $key, int|string $value): array|false;
	public function create(string $source, array $data): array|false;
	public function update(string $source, int|string $id, array $data): array|false;
	public function delete(string $source, int|string $id): bool;
}
