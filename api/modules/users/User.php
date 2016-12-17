<?php

namespace api\modules\users;

use api\app\Encryption;
use api\app\Error;
use api\app\filters\Clear;
use api\app\MainController;
use api\app\Model;
use api\app\validators\Confirm;
use api\app\View;


class User extends MainController
{
    private $model;
    use View;
    use Model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function signAction()
    {
        $this->view("sign_in");
    }

    public function loginAction()
    {
        $this->view("log_in");
    }

    public function logoutAction()
    {
        setcookie("user_id", "", time() - 86400, "/");
        header("Location: /home");
        exit();
    }

    public function dashboardAction()
    {
        $this->view("dashboard");
    }

    /* api */
    public function getUserApi($id)
    {
        $user = $this->model->getUser("id", ["value" => Clear::run($id)]);
        return ($user) ? print json_encode($user) : http_response_code(404);
    }

    public function signApi()
    {
        $data = !empty($_POST) ? $_POST : [];
	    if (empty($_POST)) return http_response_code(400);
	    if (!$this->validator->run($data, $this->getFormRules('users')['signup'])) return http_response_code(400);
        $validated = Clear::run($_POST);
	    $validated['password'] = Encryption::hash($validated['password']);
        if (!$validated) return http_response_code(400);
        if (!Confirm::run($data['password'], $data['confirm_password'])) {
            Error::setMessage("Passwords are not identical");
            return http_response_code(400);
        }
        if (isset($validated['confirm_password'])) unset($validated['confirm_password']);
        $newUser = $this->model->newUser($validated);
        if ($newUser) {
            http_response_code(201);
            header("Location: /user/{$newUser}");
        }
        return $this;
    }

    public function loginApi()
    {
        if (empty($_POST)) return http_response_code(400);
        if (!$this->validator->run($_POST, $this->getFormRules('users')['login'])) return http_response_code(400);
        $validData = Clear::run($_POST);
        $loginUser = $this->model->getUser("email", ["value" => $validData['email']]);
        if (!$loginUser || !Encryption::verify($validData['password'], $loginUser['password'])) {
	        Error::setMessage("Password or Email is wrong");
        	return http_response_code(400);
        }
        if (isset($loginUser['password'])) unset($loginUser['password']);
        setcookie("user_id", $loginUser['id'], time() + 86400, "/");
        return print json_encode($loginUser);
    }

    public function deleteUserApi($id)
    {
        if (!$this->model->deleteUser($id)) return http_response_code(204);
    }
}
