<?php

namespace Controller;

use Core\Controller;

class ErrorController extends Controller
{
    public function index()
    {
        $view = VIEWS_PATH . 'error.php';

        $this->view($view);
    }
}