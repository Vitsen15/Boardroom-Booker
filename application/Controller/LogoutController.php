<?php

namespace Controller;

use Core\Application;
use Core\Controller;

class LogoutController extends Controller
{

    /**
     * Default controller route.
     */
    public function index()
    {
        session_start();
        unset($_SESSION['accessToken']);
        session_write_close();

        Application::getInstance()->redirectUnauthorized();
    }
}