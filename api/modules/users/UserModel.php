<?php

namespace api\modules\users;

use api\app\Model;

class UserModel
{
    use Model;

    public function newUser($data)
    {
        $query = "INSERT INTO users (first_name, surname, email, password, gender) VALUES (:first_name, :surname, :email, :password, :gender)";
        return $this->create($query, $data, true);
    }

    public function getUser($field, $params)
    {
        return $this->read("SELECT id, first_name, surname, email, gender, password FROM users WHERE {$field} = :value LIMIT 1", $params, false, false);
    }

    public function deleteUser($id)
    {
        return $this->delete("DELETE FROM users WHERE id = :id", $id);
    }
}
