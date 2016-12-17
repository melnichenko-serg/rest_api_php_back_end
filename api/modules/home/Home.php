<?php

namespace api\modules\home;

use api\app\filters\Clear;
use api\app\MainController;
use api\app\View;

class Home extends MainController
{
    use View;
    private $model;

    /**
     * Home constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new HomeModel();
    }


    /***** views ****/
    public function indexAction()
    {
        $this->view("homepage");
    }

    /***** api ****/
    public function newFeedbackApi()
    {
        if (empty($_POST)) return http_response_code(400);
        if (!$this->validator->run($_POST, $this->getFormRules("home")['feedback'])) return http_response_code(400);
        $validatedFeedback = Clear::run($_POST);
        $newFeedback = $this->model->insertFeedback($validatedFeedback);
        if (!$newFeedback) return http_response_code(400);
        http_response_code(201);
        header("Location: /feedback/{$newFeedback}");
        return $this;
    }
    
    public function getReferencesApi()
    {
        return print json_encode($this->model->getReferences());
    }

    public function getHousingAssocApi()
    {
        return print json_encode($this->model->getHousingAssoc());
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

    public function getArticlesApi()
    {
        $articles = $this->model->getArticles();
        return (count($articles) > 0) ? print json_encode($this->model->getArticles()) : http_response_code(204);
    }

    public function getBannersApi()
    {
        $banners = $this->model->getBanners();
        return (count($banners) > 0) ? print json_encode($banners) : http_response_code(204);
    }
}
