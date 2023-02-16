<?php
declare(strict_types=1);

namespace Src\App\Validator;

use Src\App\Model\Record;

abstract class ModelValidation
{
	protected Record $model;
	protected string $action;
	protected Validator $validator;

	public function __construct(Record $model, string $action = 'default')
	{
		$this->model = $model;
		$this->action = $action;
		$this->createValidator();
	}

	protected function createValidator()
	{
		$this->validator = new Validator(__CLASS__ . '_' . $this->action, $this->model::class);
		$this->addRules();
	}

	abstract protected function addRules();

	public function isValid()
	{
		return $this->validator->validate($this->model);
	}
}
