<?php

namespace Controller;

use Core\Controller;
use Model\User;

class RegisterController extends Controller
{
    /**
     * Default controller route.
     */
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $view = VIEWS_PATH . 'register.php';

            $this->view($view);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model = new User();

            if ($this->validateRegistrationData($_POST)) {
                $this->model->register($_POST);
            }
        }
    }

    /**
     * @param array $request - Registration request
     * @return bool|string - Validation state or view with error
     */
    private function validateRegistrationData($request)
    {
        $errorMessage = null;
        $view = VIEWS_PATH . 'register.php';

        if (strlen($request['username']) < 6) {
            $this->view($view, ['errorMessage' => 'Username is too short']);

            return false;
        } elseif (strlen($request['username']) > 15) {
            $this->view($view, ['errorMessage' => 'Username is too long']);

            return false;
        } elseif (strlen($request['password']) < 8) {
            $this->view($view, ['errorMessage' => 'Password is too short']);

            return false;
        } elseif (strlen($request['password']) > 18) {
            $this->view($view, ['errorMessage' => 'Password is too long']);

            return false;
        } elseif ($request['password'] !== $request['repeat-password']) {
            $this->view($view, ['errorMessage' => 'Password not matching']);

            return false;
        }

        return true;
    }
}
