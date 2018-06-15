<?php

namespace Core;

abstract class Controller
{
    protected $model;

    /**
     * Renders provided view inside layout.
     *
     * @param string $template - Path to view
     * @param array $data
     * @return string
     */
    protected function view($template, $data = null)
    {
        if (isset($data)) {
            extract($data);
        }

        return include LAYOUT_PATH;
    }
}
