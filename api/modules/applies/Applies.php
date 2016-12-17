<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 29.11.16
 * Time: 13:45
 */

namespace api\modules\applies;


use api\app\MainController;

class Applies extends MainController
{
    private $model;

    /**
     * Applies constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AppliesModel();
    }

    public function newAppliesAction()
    {
        $data = !empty($_POST) ? $_POST : [];
        if (!empty($_FILES)) $data[key($_FILES)] = $_FILES;
        $validatedApplies = $this->validator->run($data, $this->getFormRules("applies")['newApplies']);
        if (!$validatedApplies) return false;
        print json_encode($validatedApplies);
        $newApplies = $this->model->newApplies($data);
        if ($newApplies) {
            http_response_code(201);
            header("Location: /applies/{$newApplies}");
        }
        return $this;
    }

    public function getAppliesAction($id)
    {
        $data['id'] = (int)$id;
        $applies = $this->model->getApplies($data);
        if (!$applies) {
            http_response_code(404);
            return false;
        }
        return print json_encode($applies);
    }
}