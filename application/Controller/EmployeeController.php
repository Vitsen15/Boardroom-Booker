<?php

namespace Controller;


use Core\Application;
use Core\Controller;
use Model\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $this->app->redirectUnauthorized();
        $this->model = new Employee();

        $view = VIEWS_PATH . 'employees/index.php';
        $viewData['employees'] = $this->model->getAllEmployees();

        $this->view($view, $viewData);
    }

    public function update($employeeID = null)
    {
        $this->app->redirectUnauthorized();
        $this->model = new Employee();
        $view = VIEWS_PATH . 'employees/update.php';

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->validateParameters($_REQUEST, $view);
                die;
                break;
            case 'GET':
                $this->app->sessionStart();
                $_SESSION['employeeID'] = $employeeID;
                session_write_close();

                $viewData = $viewData = $this->initUpdateAndCreateViewsRenderData();

                $this->view($view, $viewData);
        }
    }

    public function create()
    {
        $this->app->redirectUnauthorized();
        $this->model = new Employee();
        $view = VIEWS_PATH . 'employees/create.php';

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if ($this->validateParameters($_REQUEST, $view)) {
                    $this->model->createEmployee($_REQUEST['first-name'], $_REQUEST['last-name'], $_REQUEST['email']);
                    header('Location: ' . URL . '/employee');
                }
                break;
            case 'GET':
                $this->view($view);
        }
    }

    public function delete($id)
    {
        $this->model = new Employee();
        $this->model->deleteEmployeeByID($id);

        header('Location: ' . URL . '/employee');
    }

    protected function validateParameters($request, $view)
    {
        $viewData = $this->initUpdateAndCreateViewsRenderData();

        if (strlen($request['first-name']) > 30 || strlen($request['first-name']) < 3) {
            $viewData['error'] = 'First name length cannot be less than 3 or more than 30 characters';

            $this->view($view, $viewData);
            return false;
        }
        if (strlen($request['last-name']) > 30 || strlen($request['last-name']) < 3) {
            $viewData['error'] = 'Last name length cannot be less than 3 or more than 30 characters';

            $this->view($view, $viewData);
            return false;
        }
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $viewData['error'] = 'Please enter valid email';

            $this->view($view, $viewData);
            return false;
        }

        return true;
    }

    protected function initUpdateAndCreateViewsRenderData()
    {
        $this->app->sessionStart();
        $viewData['employee'] = (new Employee())->findEmployeeByID($_SESSION['employeeID']);

        return $viewData;
    }
}