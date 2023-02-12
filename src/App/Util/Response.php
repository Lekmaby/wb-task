<?php
declare(strict_types=1);

namespace Src\App\Util;

class Response
{
	/**
	 * Send json response
	 * @param mixed $data Data for response
	 * @return void
	 */
	public static function sendJson(mixed $data): void
	{
		header("Content-Type: application/json");
		header("Access-Control-Allow-Origin: *");
		try {
			echo json_encode($data, JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {
			self::sendException($e);
		}
		exit();
	}

	/**
	 * Sends error response
	 * @param int $code HTTP status code
	 * @param string $message Optional error message
	 * @return void
	 */
	public static function sendError(int $code, string $message = ''): void
	{
		header("Access-Control-Allow-Origin: *");
		http_response_code($code);
		if ($message !== '') {
			try {
				echo json_encode(['error' => true, 'message' => $message, 'code' => $code], JSON_THROW_ON_ERROR);
			} catch (\JsonException $e) {
				self::sendException($e);
			}
		}
		exit();
	}

	/**
	 * Sends exception
	 * @param \Exception $e Exception
	 * @return void
	 */
	public static function sendException(\Exception $e): void
	{
		header("Access-Control-Allow-Origin: *");
		http_response_code(400);

		try {
			echo json_encode([
				'error'   => true,
				'code'    => $e->getCode(),
				'message' => $e->getMessage(),
				'file'    => $e->getFile(),
				'trace'   => $e->getTrace(),
			], JSON_THROW_ON_ERROR);
		} catch (\JsonException $e) {
			echo $e->__toString();
		}

		exit();
	}
}
