<?php
/**
 * Created by PhpStorm | User: MacPro | Date: 19.11.16 | Time: 23:04
 */

namespace api\modules\pages;

use api\app\MainController;

class Page extends MainController
{
    private $model;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new PageModel();
    }



    public function getPage($page)
    {

        $contentPage = $this->model->getPageByParam("id", $page);
        if (!$contentPage) {
            http_response_code(404);
            return false;
        }
        print json_encode($contentPage);
    }
}
