<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 29.11.16
 * Time: 13:46
 */

namespace api\modules\applies;


use api\app\Model;

class AppliesModel
{
    use Model;

    public function newApplies($data)
    {
        $query = "INSERT INTO `applies` (first_name, surname, email, phone, message, cv_name, jobs_id) VALUES (:first_name, :surname, :email, :phone, :message, :cv_name, :jobs_id)";
        return $this->create($query, $data, true);
    }

    public function getApplies($id)
    {
        $query = "SELECT title, banner_title, banner_name, content, author_id, created_at FROM applies WHERE id = :id LIMIT 1";
        return $this->read($query, $id, true, true);
    }
}