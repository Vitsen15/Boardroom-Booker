<?php

namespace Core;

abstract class Controller
{
    protected $model;

    /**
     * Renders provided view inside layout or standalone.
     *
     * @param string $template - Path to view
     * @param array $data
     * @param bool $renderWithLayout
     * @return string
     */
    protected function view($template, $data = null, $renderWithLayout = true)
    {
        if (isset($data)) {
            extract($data);
        }

        if ($renderWithLayout){
            return include LAYOUT_PATH;
        } else {
            include $template;
        }
    }
}
