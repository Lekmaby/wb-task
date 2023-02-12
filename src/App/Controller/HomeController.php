<?php
declare(strict_types=1);

namespace Src\App\Controller;

class HomeController extends Controller
{
	/**
	 * Return available routes
	 * @return array
	 */
	public function index(): array
	{
		return ['routes' => [
			['name' => 'index', 'method' => 'GET', 'path' => '/', 'params' => null],
			['name'   => 'items', 'method' => 'GET', 'path' => '/items',
			 'params' => ['limit' => 'number', 'offset' => 'number'],
			],
		],
		];
	}
}


