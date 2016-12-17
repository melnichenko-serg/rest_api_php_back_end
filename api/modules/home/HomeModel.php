<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 14.12.16
 * Time: 13:54
 */

namespace api\modules\home;


use api\app\Model;

class HomeModel
{
    use Model;

    public function getReferences()
    {
        return $this->read("SELECT `id`, `name` FROM `references`", [], true, false);
    }

    public function getHousingAssoc()
    {
        return $this->read("SELECT `id`, `name` FROM `housing_assoc`", [], true, false);
    }

    public function insertFeedback($feedback)
    {
        $query = "INSERT INTO `feedbacks` (first_name, surname, phone, email, contact_method, housing_assoc_id, references_id, specify) 
                  VALUES (:first_name, :surname, :phone, :email, :contact_method, :housing_assoc_id, :references_id, :specify)";
        return $this->create($query, $feedback, true);
    }

    public function getOneArticle($id)
    {
        return $this->read("SELECT `banner_name`, `content` FROM articles WHERE id = :id LIMIT 1", $id, false, true);
    }

    public function getArticles()
    {
        $query = "SELECT `articles`.`id`, `banner_name`, `title`, `content`, `users`.`first_name` FROM articles
                  INNER JOIN `users` ON `articles`.`author_id` = `users`.`id`
                  ORDER BY `articles`.`created_at` DESC LIMIT 100";
        return $this->read($query, [], true, true);
    }

    public function getBanners()
    {
        return $this->read("select `baner_title`, `baner_name` from pages limit 100", [], true, false);
    }
}