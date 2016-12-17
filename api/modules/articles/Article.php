<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 28.11.16
 * Time: 10:53
 */

namespace api\modules\articles;

use api\app\MainController;

class Article extends MainController
{
    private $model;

    /**
     * Article constructor.
     * @internal param $model
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new ArticleModel();
    }

    public function newArticleAction()
    {
        $data = !empty($_POST) ? $_POST : [];
        $validatedData = $this->validator->run($data, $this->getFormRules("articles")['newArticle']);
        if (!$validatedData) return false;
        $newArticle = $this->model->newArticle($data);
        if ($newArticle) {
            http_response_code(201);
            header("Location: /article/{$newArticle}");
        }
        return $this;
    }

    public function getArticlesAction()
    {
        if (!isset($_SERVER["HTTP_RANGE"])) {
            http_response_code(416);
            return false;
        }
        $range = $_SERVER["HTTP_RANGE"];
        $range = explode("-", $range);
        $start = array_shift($range);
        $quantity = array_shift($range);
        $params = ["start" => (int)$start, "finish" => (int)$quantity];
        $countArticles = $this->model->getCountArticles()['count'];
        $finish = (($countArticles + $quantity) - $countArticles);
        $queryParams = null;
        if (isset($_SERVER["QUERY_STRING"])) {
            parse_str($_SERVER["QUERY_STRING"], $queryParams);
        }
        $articlesByRange = $this->model->getArticles($params, $queryParams);
        if ($quantity > $countArticles) {
            http_response_code(416);
            return false;
        }
        header("Content-Range: items {$start}-{$finish}/{$countArticles}");
        return print json_encode($articlesByRange);
    }

    public function getArticleApi($id)
    {
        $data['id'] = (int)$id;
        $article = $this->model->getOneArticle($data);
        if (!$article) {
            http_response_code(404);
            return false;
        }
        return print json_encode($article);
    }
}