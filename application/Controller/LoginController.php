<?php

namespace Controller;

use Core\Controller;
use Model\User;

class LoginController extends Controller
{
    /**
     * Default controller route.
     */
    public function index()
    {
        $view = VIEWS_PATH . 'login.php';

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view($view);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model = new User();

            if ($this->validateLoginData($_POST)) {
                if (!$this->model->login($_POST)) {
                    $this->view($view, ['errorMessage' => 'Username or password is incorrect']);
                } else {
                    $this->view(VIEWS_PATH . 'index.php');
                }

            }
        }
    }

    private function validateLoginData($request)
    {
        $errorMessage = null;
        $view = VIEWS_PATH . 'login.php';

        if (strlen($request['username']) < USERNAME_MIN_LENGTH) {
            $this->view($view, ['errorMessage' => 'Username is too short']);

            return false;
        } elseif (strlen($request['username']) > USERNAME_MAX_LENGTH) {
            $this->view($view, ['errorMessage' => 'Username is too long']);

            return false;
        } elseif (strlen($request['password']) < PASSWORD_MIN_LENGTH) {
            $this->view($view, ['errorMessage' => 'Password is too short']);

            return false;
        } elseif (strlen($request['password']) > PASSWORD_MAX_LENGTH) {
            $this->view($view, ['errorMessage' => 'Password is too long']);

            return false;
        }

        return true;
    }
}