<?php

namespace api\app;

use api\app\validators\Validator;

abstract class MainController
{
	protected $validator;

	/**
	 * BaseFactory constructor.
	 */
	public function __construct()
	{
		$this->validator = new Validator();
	}

	/**
	 * @param $module
	 * @return mixed|null
	 */
	public function getFormRules($module)
	{
		$path = "api/modules/{$module}/formRules.php";
		if (!file_exists($path)) {
			Error::setMessage("Rules not found");
			return http_response_code(404);
		} else {
			/** @noinspection PhpIncludeInspection */
			return include_once $path;
		}
	}
}
