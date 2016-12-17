<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 28.11.16
 * Time: 10:56
 */

namespace api\modules\articles;

use api\app\filters\Clear;
use api\app\Model;

class ArticleModel
{
    use Model;

    public function newArticle($data)
    {
        $query = "INSERT INTO articles (title, banner_title, banner_name, content, author_id) VALUES (:title, :banner_title, :banner_name, :content, :author_id)";
        return $this->create($query, $data, true);
    }

    public function getArticles($params, $queryParams = null)
    {
        $field = "id";
        $direction = "ASC";
        if (!empty($queryParams)) {
            isset($queryParams['sort']) && $field = Clear::run($queryParams['sort']);
            isset($queryParams['direction']) && $direction = Clear::run($queryParams['direction']);
        }
        $query = "SELECT title, banner_title, banner_name, content, author_id, created_at FROM articles ORDER BY {$field} {$direction} LIMIT :start, :finish";
        return $this->read($query, $params, true, true);
    }

    public function getCountArticles()
    {
        return $this->read("SELECT COUNT(*) AS count from articles", false, false, false);
    }

    public function getOneArticle($id)
    {
        $query = "SELECT title, banner_title, banner_name, content, author_id, created_at FROM articles WHERE id = :id LIMIT 1";
        return $this->read($query, $id, false, true);
    }
}