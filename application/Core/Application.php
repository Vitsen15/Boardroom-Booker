<?php

namespace Core;

use Controller\CalendarController;
use Controller\ErrorController;
use Core\Traits\Singleton;

class Application
{
    use Singleton;

    const CONTROLLERS_DIR = APP . 'Controller' . DIRECTORY_SEPARATOR;

    /** @var string The controller name */
    private $controllerName;

    /** @var string The method (of the above controller), often also named "action" */
    private $actionName;

    /** @var array URL parameters */
    private $params;

    /** @var Controller The controller instance */
    private $controller;

    /**
     * "Start" the application:
     * Analyze the URL elements and calls the according controller/method or the fallback
     */
    public function start()
    {
        $this->splitUrl();

        if (!$this->controllerName) {
            $page = new CalendarController();
            $page->index();
        } elseif (file_exists(self::CONTROLLERS_DIR . ucfirst($this->controllerName) . 'Controller.php')) {
            $controller = "\\Controller\\" . ucfirst($this->controllerName) . 'Controller';
            $this->controller = new $controller();

            if (method_exists($this->controller, $this->actionName) &&
                is_callable([$this->controller, $this->actionName])) {

                if (!empty($this->params)) {
                    call_user_func_array(array($this->controller, $this->actionName), $this->params);
                } else {
                    $this->controller->{$this->actionName}();
                }
            } else {
                if (strlen($this->actionName) == 0) {
                    $this->controller->index();
                } else {
                    $page = new ErrorController();
                    $page->index();
                }
            }
        } else {
            $page = new ErrorController();
            $page->index();
        }
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        $requestUri = $_SERVER['REQUEST_URI'];

        if (isset($requestUri)) {
            $url = trim($requestUri, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            $this->controllerName = isset($url[0]) ? $url[0] : null;
            $this->actionName = isset($url[1]) ? $url[1] : null;

            unset($url[0], $url[1]);

            $this->params = array_values($url);
        }
    }

    /**
     * @return bool
     */
    public function checkAuth()
    {
        $this->sessionStart();

        if (
            !isset($_SESSION['accessToken']) ||
            !isset($_COOKIE['accessToken']) ||
            $_SESSION['accessToken'] != $_COOKIE['accessToken']
        ) {
            return false;
        } else return true;
    }

    public function redirectUnauthorized()
    {
        $this->sessionStart();

        if (
            !isset($_SESSION['accessToken']) ||
            !isset($_COOKIE['accessToken']) ||
            $_SESSION['accessToken'] != $_COOKIE['accessToken']
        ) {
            header('Location: ' . URL . '/login');
        }
    }

    protected function sessionStart()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}
