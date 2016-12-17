<?php

namespace api\app\validators;

use api\app\Error;

class Validator
{

    /**
     * @param $data
     * @param $rules
     * @return array|bool
     */
    public function run(array $data, $rules)
    {
        if (!$data || !$rules) {
            Error::setMessage("Internal validation error");
            http_response_code(400);
            return false;
        }
        $result = [];
        foreach ($data as $field => $value) {
            if (!in_array($field, array_keys($rules))) {
                Error::setMessage(strtoupper($field) . " unexpected field");
                http_response_code(400);
                return false;
            }
            if (empty($rules[$field])) {
                $result[$field] = $value;
                continue;
            }
            $arrRules = explode("|", $rules[$field]);
            foreach ($arrRules as $rule) {
                if (empty($rule)) continue 2;
				$arr = explode(":", $rule);
				$validator = "api\\app\\validators\\" . ucfirst($arr[0]);
				if (!$validator::run($value, $arr[1] ?? null)) Error::setMessage("Field an ". strtoupper($field) ." is not passed validation ({$arr[0]})");
            }
        }
        return count(Error::getMessage()) > 0 ? false : true;
    }
}
