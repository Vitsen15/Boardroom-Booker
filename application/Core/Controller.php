<?php

namespace Core;

abstract class Controller
{
    protected $model;

    /**
     * Default controller route.
     *
     * @return string
     */
    abstract public function index();

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
