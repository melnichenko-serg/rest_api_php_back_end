<?php

namespace api\app;

use api\app\filters\Clear;
use Exception;
use PDO;
use PDOException;

trait Model
{
	/**
	 * @return PDO
	 * @internal param string $query
	 * @internal param array $params
	 */
	private function connect()
	{
		return DataBase::getInstance()->getConnect();
	}

	public function create(string $query, array $params = [], bool $getId)
	{
		try {
			$stmt = $this->connect()->prepare($query);
			foreach ($params as $param => $value)
				$stmt->bindValue(":{$param}", $value, PDO::PARAM_STR);
			if (!$getId) return $stmt->execute();
			else {
				$res = $stmt->execute();
				return $res ? (int)$this->connect()->lastInsertId() : false;
			}
		} catch (PDOException $exception) {
//			throw new PDOException($exception->getMessage());
			Error::setMessage($exception->getMessage());
			return false;
		}
	}

	/**
	 * @param string $query
	 * @param array - $params
	 * @param bool $fetchTypeAll
	 * @param bool $bindTypeInt
	 * @return array|bool
	 * @throws Exception - pz
	 * @internal param bool $getTableRows
	 * @internal param $string - $query
	 * @internal param $bool - $fetchTypeAll if not returned a single result
	 * @internal param $bool - $bindTypeInt if the parameter must be an integer
	 */
	public function read(string $query, array $params, bool $fetchTypeAll, bool $bindTypeInt)
	{
		try {
			$stmt = $this->connect()->prepare($query);
			if (!$params) $stmt->execute();
			else {
				foreach ($params as $param => $value)
					$bindTypeInt ? $stmt->bindValue(":{$param}", $value, PDO::PARAM_INT) : $stmt->bindValue(":{$param}", $value, PDO::PARAM_STR);
				$stmt->execute();
			}
			return $fetchTypeAll ? $stmt->fetchAll() : $stmt->fetch();
		} catch (PDOException $exception) {
//			throw new PDOException($exception->getMessage());
			Error::setMessage($exception->getMessage());
			return false;
		}
	}

	public function update(string $query, array $params)
	{
		try {
			$param = $value = null;
			$stmt = $this->connect()->prepare($query);
			foreach ($params as $param => $value)
				if (is_array($value)) return false;
			$stmt->bindValue(":{$param}", $value);
			$stmt->execute();
			return $stmt->rowCount();
		} catch (PDOException $exception) {
//			throw new PDOException($exception->getMessage());
			Error::setMessage($exception->getMessage());
			return false;
		}
	}

	public function delete(string $query, $id)
	{
		try {
			$stmt = $this->connect()->prepare($query);
			$stmt->execute([":id" => $id]);
			return $stmt->rowCount();
		} catch (PDOException $exception) {
//			throw new PDOException($exception->getMessage());
			Error::setMessage($exception->getMessage());
			return false;
		}
	}
}