<?php

namespace Core;

abstract class Controller
{
    protected $model;

    /**
     * Default controller route.
     */
    abstract public function index();



    protected function checkAuth()
    {
        session_start();

        if (
            !isset($_SESSION['accessToken']) ||
            !isset($_COOKIE['accessToken']) ||
            $_SESSION['accessToken'] != $_COOKIE['accessToken']
        ) {
            header('Location: ' . URL . '/login');
        } else return;
    }

    /**
     * Renders provided view inside layout.
     *
     * @param string $template - Path to view
     * @param array $data
     * @return string
     */
    protected function view($template, $data = null)
    {
        extract($data);

        return include LAYOUT_PATH;
    }
}
